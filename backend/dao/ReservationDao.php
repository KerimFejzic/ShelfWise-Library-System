<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class ReservationDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reservations', ['user_id', 'book_id', 'reserved_at', 'status']);
    }
}
