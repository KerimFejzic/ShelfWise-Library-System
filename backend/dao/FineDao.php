<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class FineDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('fines', ['loan_id', 'amount', 'status']);
    }
}
