<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/LoanDao.php';
require_once __DIR__ . '/../dao/BookDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
class LoanService extends BaseService {
    public function __construct(?LoanDao $dao=null){parent::__construct($dao ?? new LoanDao(),'Loan');}
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['user_id','book_id','borrowed_at','due_at']);
        foreach (['user_id','book_id'] as $field) Validator::integer($data,$field,1);
        foreach (['borrowed_at','due_at'] as $field) Validator::date($data,$field);
        Validator::date($data,'returned_at',true);
        Validator::choice($data,'status',['borrowed','returned','overdue']);
        if ($id!==null && (isset($data['user_id']) || isset($data['book_id']))) throw new ValidationException('Cannot change the user or book of an existing loan.');
        if (isset($data['user_id']) && !(new UserDao())->get_by_id((int)$data['user_id'])) throw new ValidationException('User does not exist.');
        if (isset($data['book_id']) && !(new BookDao())->get_by_id((int)$data['book_id'])) throw new ValidationException('Book does not exist.');
        $old=$id ? $this->get_by_id($id) : [];
        $start=$data['borrowed_at'] ?? ($old['borrowed_at']??null);
        $due=$data['due_at']??($old['due_at']??null);
        if ($start && $due && $start>$due) throw new ValidationException('Due date must be on or after borrowed date.');
        if ($id===null && !empty($data['returned_at'])) throw new ValidationException('New loan cannot have returned_at.');
        if ($id===null && isset($data['status']) && $data['status']!=='borrowed') throw new ValidationException('New loan must be borrowed.');
        if ($id && isset($data['status']) && ($old['status']==='returned') && $data['status']!=='returned') throw new ValidationException('Returned loan cannot be reopened.');
    }
    public function add(array $data):array {
        $this->validate($data);
        $this->dao->begin_transaction();
        try {
            $bookDao=new BookDao();
            // Lock row before inventory change to avoid double-borrowing.
            $book=$this->dao->lock_book((int)$data['book_id']);
            if (!$book || (int)$book['available_copies']<1) throw new ValidationException('No available copies.');
            $bookDao->update(['available_copies'=>(int)$book['available_copies']-1],(int)$book['id']);
            $data['status']='borrowed';
            $result=$this->dao->add($data);
            $this->dao->commit();return $result;
        } catch (Throwable $e) { $this->dao->rollback();throw $e; }
    }
    public function update(array $data,int $id):array {
        $old=$this->get_by_id($id);$this->validate($data,$id);
        $newStatus=$data['status']??$old['status'];
        if ($newStatus==='returned' && $old['status']!=='returned') {
            $this->dao->begin_transaction();
            try {
                $book=$this->dao->lock_book((int)$old['book_id']);
                if (!$book) throw new ValidationException('Related book missing.');
                $newAvailable=(int)$book['available_copies']+1;
                if ($newAvailable>(int)$book['total_copies']) throw new ValidationException('Book inventory is inconsistent.');
                (new BookDao())->update(['available_copies'=>$newAvailable],(int)$old['book_id']);
                $data['returned_at']=$data['returned_at']??date('Y-m-d');
                $this->dao->update($data,$id);
                $this->dao->commit();return $this->get_by_id($id);
            } catch (Throwable $e) { $this->dao->rollback();throw $e; }
        }
        if ($newStatus!=='returned' && isset($data['returned_at']) && $data['returned_at']) throw new ValidationException('Non-returned loan cannot have returned_at.');
        return parent::update($data,$id);
    }
    public function delete(int $id):array {
        $old=$this->get_by_id($id);
        $this->dao->begin_transaction();
        try {
            if ($old['status']!=='returned') {
                $book=$this->dao->lock_book((int)$old['book_id']);
                if ($book) (new BookDao())->update(['available_copies'=>(int)$book['available_copies']+1],(int)$old['book_id']);
            }
            $result=parent::delete($id);$this->dao->commit();return $result;
        } catch (Throwable $e) {$this->dao->rollback();throw $e;}
    }
}
