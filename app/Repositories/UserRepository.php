<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserWriteRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;

final class UserRepository extends BaseRepository implements UserReadRepositoryInterface, UserWriteRepositoryInterface, UserSpecializationRepositoryInterface {

    public function findByEmail(string $email): ?array{
        try {
            return $this->queryOne(sql: 'SELECT * FROM users WHERE email = ? and deleted_at is null', params: [$email]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function getUserById(int $id): ?array
    {
        return $this->getUserBy(column: 'id', value: $id);
    }

    public function getUserByIdOnly(int $id): ?array
    {
        try {
            return $this->queryOne(sql: 'SELECT * FROM users WHERE id = ? AND deleted_at IS NULL', params: [$id]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function getUserSpecializations(int $id): array
    {
        try {
            $sql = "SELECT 
                        s.id as specialization_id, 
                        s.name as specialization_name,
                        s.description as specialization_description,
                        s.created_at as specialization_created_at,
                        s.updated_at as specialization_updated_at
                    FROM users u
                    LEFT JOIN user_specializations us ON u.id = us.user_id
                    LEFT JOIN specializations s ON us.specialization_id = s.id
                    WHERE u.id = ? AND u.deleted_at IS NULL";
            
            return $this->query(sql: $sql, params: [$id]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    private function getUserBy(string $column, $value): ?array
    {
        $sql = "SELECT 
                    u.*, 
                    s.id as specialization_id, 
                    s.name as specialization_name,
                    s.description as specialization_description,
                    s.created_at as specialization_created_at,
                    s.updated_at as specialization_updated_at
                FROM users u
                LEFT JOIN user_specializations us ON u.id = us.user_id
                LEFT JOIN specializations s ON us.specialization_id = s.id
                WHERE u.{$column} = ? AND u.deleted_at IS NULL";

        try {
            return $this->query(sql: $sql, params: [$value]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function getAllUsers(int $page): array {
        try {
            return $this->query(sql: 'SELECT * FROM users WHERE deleted_at is null LIMIT 10 OFFSET ?', params: [($page - 1) * 10]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function createUser(array $params): void {
        try {
            $this->execute(sql: 'INSERT INTO users (first_name, last_name, email, password, biography, price, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', params: $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function updateUser(array $params): void
    {
        try {
            $this->execute(sql: "UPDATE users SET first_name = ?, last_name = ?, role = ?, biography = ?, price = ?, updated_at = ? WHERE id = ?", params: $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function deleteUser(array $params): void
    {
        try {
            $this->execute(sql: 'UPDATE users SET deleted_at = ?, email = ? WHERE id = ?', params: $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function saveUserSpecializations(int $userId, array $specializationIds): void
    {
        try {
            $placeholders = str_repeat('(?, ?), ', count($specializationIds) - 1) . '(?, ?)';
            $params = [];
            foreach ($specializationIds as $specializationId) {
                $params[] = $userId;
                $params[] = $specializationId;
            }
            
            $sql = "INSERT INTO user_specializations (user_id, specialization_id) VALUES {$placeholders}";
            $this->execute(sql: $sql, params: $params);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function deleteUserSpecializations(int $userId): void
    {
        try {
            $this->execute(sql: 'DELETE FROM user_specializations WHERE user_id = ?', params: [$userId]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }

    public function getMentorsBySpecialization(int $specializationId): array
    {
        try {
            $sql = "SELECT DISTINCT u.id, u.first_name, u.last_name, u.biography, u.price
                    FROM users u
                    INNER JOIN user_specializations us ON u.id = us.user_id
                    WHERE us.specialization_id = ? AND u.role = 'mentor' AND u.deleted_at IS NULL
                    ORDER BY u.first_name, u.last_name";
            
            return $this->query(sql: $sql, params: [$specializationId]);
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }
}