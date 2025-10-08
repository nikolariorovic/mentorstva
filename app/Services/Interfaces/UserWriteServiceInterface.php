<?php
namespace App\Services\Interfaces;

interface UserWriteServiceInterface
{
    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function createUser(array $data): void;
    /**
     * @param int $id
     * @param array<string, mixed> $data
     * @return void
     */
    public function updateUser(int $id, array $data): void;
    public function deleteUser(int $id): void;
}