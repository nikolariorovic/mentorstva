<?php
namespace App\Services\Interfaces;

use App\Models\User;

interface UserReadServiceInterface
{
    /**
     * @param int $page
     * @return list<User>
     */
    public function getPaginatedUsers(int $page): array;
    public function getUserById(int $id): ?User;
    /**
     * @param int $specializationId
     * @return array<string, mixed>
     */
    public function getMentorsBySpecialization(int $specializationId): array;
}