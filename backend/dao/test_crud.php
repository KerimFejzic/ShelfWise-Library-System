<?php
declare(strict_types=1);
/**
 * All six entities: Create, Read, Update, Delete.
 * Changes are rolled back, so sample records remain unchanged.
 * Run only in a local development database: php backend/dao/test_crud.php
 */
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/CategoryDao.php';
require_once __DIR__ . '/BookDao.php';
require_once __DIR__ . '/LoanDao.php';
require_once __DIR__ . '/ReservationDao.php';
require_once __DIR__ . '/FineDao.php';

function assert_true(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$users = new UserDao();
$categories = new CategoryDao();
$books = new BookDao();
$loans = new LoanDao();
$reservations = new ReservationDao();
$fines = new FineDao();
$inTransaction = false;

try {
    $users->begin_transaction();
    $inTransaction = true;
    $stamp = bin2hex(random_bytes(4));

    // CREATE: use freshly created rows to satisfy foreign keys.
    $user = $users->add([
        'full_name' => 'CRUD Test Member',
        'email' => "crud_{$stamp}@example.test",
        'password_hash' => password_hash('local-test-only', PASSWORD_DEFAULT),
        'role' => 'member',
    ]);
    $category = $categories->add(['name' => "Test Category {$stamp}", 'description' => 'Temporary test']);
    $book = $books->add([
        'category_id' => $category['id'], 'title' => "Test Book {$stamp}",
        'author' => 'Test Author', 'isbn' => "TEST-{$stamp}",
        'total_copies' => 2, 'available_copies' => 2,
    ]);
    $loan = $loans->add([
        'user_id' => $user['id'], 'book_id' => $book['id'],
        'borrowed_at' => '2026-11-01', 'due_at' => '2026-11-14',
        'status' => 'borrowed',
    ]);
    $reservation = $reservations->add([
        'user_id' => $user['id'], 'book_id' => $book['id'],
        'reserved_at' => '2026-11-01', 'status' => 'pending',
    ]);
    $fine = $fines->add(['loan_id' => $loan['id'], 'amount' => 3.50, 'status' => 'unpaid']);
    $cases = [
        [$users, $user, 'full_name', 'CRUD Updated Member'],
        [$categories, $category, 'description', 'Updated description'],
        [$books, $book, 'title', 'Updated Test Book'],
        [$loans, $loan, 'status', 'returned'],
        [$reservations, $reservation, 'status', 'cancelled'],
        [$fines, $fine, 'status', 'paid'],
    ];
    foreach ($cases as [$dao, $record, $field, $newValue]) {
        $name = get_class($dao);
        $id = (int) $record['id'];
        assert_true($dao->get_by_id($id) !== null, "{$name} READ failed");
        assert_true($dao->update([$field => $newValue], $id) === 1, "{$name} UPDATE failed");
        assert_true($dao->get_by_id($id)[$field] === $newValue, "{$name} updated value incorrect");
        echo "{$name}: CREATE / READ / UPDATE OK" . PHP_EOL;
    }
    // DELETE in reverse FK dependency order.
    foreach ([[$fines, $fine], [$reservations, $reservation], [$loans, $loan],
              [$books, $book], [$categories, $category], [$users, $user]] as [$dao, $record]) {
        $name = get_class($dao);
        $id = (int) $record['id'];
        assert_true($dao->delete($id) === 1, "{$name} DELETE failed");
        assert_true($dao->get_by_id($id) === null, "{$name} DELETE verification failed");
        echo "{$name}: DELETE OK" . PHP_EOL;
    }
    $users->rollback();
    $inTransaction = false;
    echo 'PASS: all six entities passed CRUD checks; transaction rolled back.' . PHP_EOL;
} catch (Throwable $e) {
    if ($inTransaction) {
        try { $users->rollback(); } catch (Throwable $ignored) {}
    }
    fwrite(STDERR, 'FAIL: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
