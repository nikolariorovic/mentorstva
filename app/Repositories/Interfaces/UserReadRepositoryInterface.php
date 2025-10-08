<?php
namespace App\Repositories\Interfaces;

interface UserReadRepositoryInterface
{
    /**
     * @param string $email
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array;
    /**
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getAllUsers(int $page): array;
    /**
     * @param int $id
     * @return list<array<string, mixed>>
     */
    public function getUserById(int $id): array;
    /**
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function getUserByIdOnly(int $id): ?array;
    /**
     * @param int $specializationId
     * @return array<string, mixed>
     */
    public function getMentorsBySpecialization(int $specializationId): array;
}