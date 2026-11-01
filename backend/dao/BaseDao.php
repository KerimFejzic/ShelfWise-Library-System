<?php
declare(strict_types=1);

/** Reusable MySQL/PDO access layer, following Lab 03's pattern. */
class BaseDao
{
    protected string $table_name;
    protected array $columns;
    private static ?PDO $connection = null;

    public function __construct(string $table_name, array $columns)
    {
        $this->table_name = $table_name;
        $this->columns = $columns;
    }

    protected function connection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../config/config.php';
            $host = $config['db_host'];
            $port = $config['db_port'] ?? 3306;
            $db = $config['db_name'];
            $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
            self::$connection = new PDO($dsn, $config['db_user'], $config['db_password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$connection;
    }

    protected function query(string $sql, array $params = []): array
    {
        $statement = $this->connection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    protected function query_unique(string $sql, array $params = []): ?array
    {
        $statement = $this->connection()->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();
        return $row === false ? null : $row;
    }

    protected function execute(string $sql, array $params = []): int
    {
        $statement = $this->connection()->prepare($sql);
        $statement->execute($params);
        return $statement->rowCount();
    }

    protected function only_columns(array $entity): array
    {
        return array_intersect_key($entity, array_flip($this->columns));
    }

    public function get_all(): array
    {
        return $this->query("SELECT * FROM {$this->table_name} ORDER BY id");
    }

    public function get_by_id(int $id): ?array
    {
        return $this->query_unique("SELECT * FROM {$this->table_name} WHERE id = :id", ['id' => $id]);
    }

    public function add(array $entity): ?array
    {
        $data = $this->only_columns($entity);
        if (!$data) { throw new InvalidArgumentException('No valid columns supplied'); }
        $names = array_keys($data);
        $cols = implode(', ', $names);
        $placeholders = ':' . implode(', :', $names);
        $this->execute("INSERT INTO {$this->table_name} ({$cols}) VALUES ({$placeholders})", $data);
        return $this->get_by_id((int) $this->connection()->lastInsertId());
    }

    public function update(array $entity, int $id): int
    {
        $data = $this->only_columns($entity);
        if (!$data) { return 0; }
        $sets = array_map(fn($name) => "{$name} = :{$name}", array_keys($data));
        $data['id'] = $id;
        return $this->execute("UPDATE {$this->table_name} SET " . implode(', ', $sets) . ' WHERE id = :id', $data);
    }

    public function delete(int $id): int
    {
        return $this->execute("DELETE FROM {$this->table_name} WHERE id = :id", ['id' => $id]);
    }

    public function begin_transaction(): bool { return $this->connection()->beginTransaction(); }
    public function commit(): bool { return $this->connection()->commit(); }
    public function rollback(): bool { return $this->connection()->rollBack(); }
}
