<?php
namespace App\Repositories\Interfaces;

interface UserWriteRepositoryInterface
{
    /**
     * @param array<int, float|string|null> $params
     * @return void
     */
    public function createUser(array $params): void;
    /**
     * @param array<int, float|string|null> $params
     * @return void
     */
    public function updateUser(array $params): void;
    /**
     * @param array<int, int|string> $params
     * @return void
     */
    public function deleteUser(array $params): void;
}