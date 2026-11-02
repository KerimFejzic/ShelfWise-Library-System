<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class BookDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('books', ['category_id', 'title', 'author', 'isbn', 'total_copies', 'available_copies']);
    }
}
