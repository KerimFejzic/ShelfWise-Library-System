<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/FineDao.php';
require_once __DIR__ . '/../dao/LoanDao.php';
class FineService extends BaseService {
    public function __construct(?FineDao $dao=null){parent::__construct($dao ?? new FineDao(),'Fine');}
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['loan_id','amount']);
        Validator::integer($data,'loan_id',1);Validator::choice($data,'status',['unpaid','paid','waived']);
        if (array_key_exists('amount',$data) && (!is_numeric($data['amount']) || (float)$data['amount']<0)) throw new ValidationException('Amount must be nonnegative.');
        if (isset($data['loan_id'])) {
            if (!(new LoanDao())->get_by_id((int)$data['loan_id'])) throw new ValidationException('Loan does not exist.');
            foreach ($this->get_all() as $fine) if ((int)$fine['loan_id']===(int)$data['loan_id'] && (int)$fine['id']!==$id) throw new ValidationException('Loan already has a fine.');
        }
    }
}
