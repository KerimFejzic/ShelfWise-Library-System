<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class LoanDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('loans', ['user_id', 'book_id', 'borrowed_at', 'due_at', 'returned_at', 'status']);
    }
}
