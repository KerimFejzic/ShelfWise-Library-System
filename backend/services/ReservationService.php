<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReservationDao.php';
require_once __DIR__ . '/../dao/BookDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
class ReservationService extends BaseService {
    public function __construct(?ReservationDao $dao=null){parent::__construct($dao ?? new ReservationDao(),'Reservation');}
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['user_id','book_id','reserved_at']);
        Validator::integer($data,'user_id',1); Validator::integer($data,'book_id',1);
        Validator::date($data,'reserved_at'); Validator::choice($data,'status',['pending','fulfilled','cancelled']);
        if (isset($data['user_id']) && !(new UserDao())->get_by_id((int)$data['user_id'])) throw new ValidationException('User does not exist.');
        if (isset($data['book_id']) && !(new BookDao())->get_by_id((int)$data['book_id'])) throw new ValidationException('Book does not exist.');
    }
}
