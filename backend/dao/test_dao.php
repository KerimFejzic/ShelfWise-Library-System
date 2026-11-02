<?php
declare(strict_types=1);
// Local read-only test: after importing database/shelfwise.sql and creating config.php.
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/CategoryDao.php';
require_once __DIR__ . '/BookDao.php';
require_once __DIR__ . '/LoanDao.php';
require_once __DIR__ . '/ReservationDao.php';
require_once __DIR__ . '/FineDao.php';

try {
    foreach ([new UserDao(), new CategoryDao(), new BookDao(), new LoanDao(), new ReservationDao(), new FineDao()] as $dao) {
        $rows = $dao->get_all();
        echo get_class($dao) . ': ' . count($rows) . ' records' . PHP_EOL;
        if ($rows) {
            $first = $dao->get_by_id((int) $rows[0]['id']);
            if ($first === null) { throw new RuntimeException('Lookup failed for ' . get_class($dao)); }
        }
    }
    echo 'Read-only DAO test completed.' . PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, 'Test failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
