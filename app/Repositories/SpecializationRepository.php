<?php
namespace App\Repositories;

use App\Repositories\Interfaces\SpecializationRepositoryInterface;

final class SpecializationRepository extends BaseRepository implements SpecializationRepositoryInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function getAll(): array
    {
        try {
            return $this->query(sql: 'SELECT * FROM specializations ORDER BY name ASC');
        } catch (\PDOException $e) {
            $this->handleDatabaseError(e: $e);
        }
    }
} 