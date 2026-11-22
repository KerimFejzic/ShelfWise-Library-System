<?php
declare(strict_types=1);
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../dao/BaseDao.php';
class BaseService {
    protected BaseDao $dao;
    protected string $entity_name;
    public function __construct(BaseDao $dao,string $entity_name) { $this->dao=$dao; $this->entity_name=$entity_name; }
    public function get_all():array { return $this->dao->get_all(); }
    public function get_by_id(int $id):array {
        $record=$this->dao->get_by_id($id);
        if (!$record) throw new NotFoundException("{$this->entity_name} not found.");
        return $record;
    }
    protected function validate(array $data,?int $id=null):void {}
    public function add(array $data):array {
        $this->validate($data);
        $record=$this->dao->add($data);
        if (!$record) throw new RuntimeException('Could not create resource.');
        return $record;
    }
    public function update(array $data,int $id):array {
        $this->get_by_id($id);
        if (!$data) throw new ValidationException('Request body cannot be empty.');
        $this->validate($data,$id);
        $this->dao->update($data,$id);
        return $this->get_by_id($id);
    }
    public function delete(int $id):array { $this->get_by_id($id); $this->dao->delete($id); return ['message'=>"{$this->entity_name} deleted."]; }
}
