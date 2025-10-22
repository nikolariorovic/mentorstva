<?php
namespace App\Repositories\Interfaces;

interface UserSpecializationRepositoryInterface
{
    /**
     * @param int $userId
     * @param array<string, mixed> $specializationIds
     * @return void
     */
    public function saveUserSpecializations(int $userId, array $specializationIds): void;
    public function deleteUserSpecializations(int $userId): void;
    /**
     * @param int $id
     * @return list<array<string, mixed>>
     */
    public function getUserSpecializations(int $id): array;
}