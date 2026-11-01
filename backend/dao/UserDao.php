<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('users', ['full_name', 'email', 'password_hash', 'role']);
    }

    // Only the authentication service should retrieve a password hash.
    public function get_by_email(string $email): ?array
    {
        return $this->query_unique('SELECT * FROM users WHERE email = :email', ['email' => $email]);
    }

    public function get_all(): array
    {
        return $this->query('SELECT id, full_name, email, role FROM users ORDER BY id');
    }

    public function get_by_id(int $id): ?array
    {
        return $this->query_unique('SELECT id, full_name, email, role FROM users WHERE id = :id', ['id' => $id]);
    }

    // Later authentication code must hash passwords before calling add/update.
}
