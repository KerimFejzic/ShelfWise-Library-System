<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';
class UserService extends BaseService {
    public function __construct(?UserDao $dao=null){parent::__construct($dao ?? new UserDao(),'User');}
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['full_name','email','password']);
        Validator::text($data,'full_name',120,2);
        Validator::email($data,'email'); Validator::choice($data,'role',['admin','member']);
        if (isset($data['password']) && (!is_string($data['password']) || strlen($data['password'])<8)) throw new ValidationException('Password must be at least 8 characters.');
        if (isset($data['email'])) { $previous=(new UserDao())->get_by_email($data['email']); if ($previous && (int)$previous['id']!==$id) throw new ValidationException('Email already exists.'); }
    }
    private function clean(array $user):array { unset($user['password_hash']);return $user; }
    public function add(array $data):array {
        $this->validate($data);
        $data['password_hash']=password_hash($data['password'],PASSWORD_BCRYPT);
        unset($data['password']);
        return $this->clean($this->dao->add($data));
    }
    public function update(array $data,int $id):array {
        $this->get_by_id($id); $this->validate($data,$id);
        if (isset($data['password'])) { $data['password_hash']=password_hash($data['password'],PASSWORD_BCRYPT); unset($data['password']); }
        unset($data['password_hash']);
        if (!$data) throw new ValidationException('No changes supplied.');
        $this->dao->update($data,$id);
        return $this->get_by_id($id);
    }
}
