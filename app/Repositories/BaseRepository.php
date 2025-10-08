<?php
namespace App\Repositories;

use Database\Database;
use App\Exceptions\DatabaseException;
use PDOException;
use PDO;

class BaseRepository {
    protected PDO $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    /**
     * @param string $sql
     * @param list<int|string|float|null> $params
     * @return list<array<string, mixed>>|array<string, mixed>
     */
    protected function query(string $sql, array $params = []): array {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }  
    }

    /**
     * @param string $sql
     * @param array<int|string, int|string|float|bool|null> $params
     * @return bool
     */
    protected function execute(string $sql, array $params = []): bool {
        try {
            $stmt = $this->db->prepare($sql);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    /**
     * @param string $sql
     * @param list<int|string|float|null> $params
     * @return list<array<string, mixed>>|array<string, mixed>|null
     */
    protected function queryOne(string $sql, array $params = []): ?array {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    /**
     * @throws DatabaseException
     */
    protected function handleDatabaseError(PDOException $e): never {
        logError('Database error: ' . $e->getMessage());
        throw new DatabaseException(message: "Database error: " . $e->getMessage());
    }

    protected function getLastInsertId(): int {
        return (int) $this->db->lastInsertId();
    }
}