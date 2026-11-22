<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class LoanDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('loans', ['user_id', 'book_id', 'borrowed_at', 'due_at', 'returned_at', 'status']);
    }
    public function lock_book(int $id): ?array
    {
        return $this->query_unique('SELECT * FROM books WHERE id = :id FOR UPDATE', ['id' => $id]);
    }
}