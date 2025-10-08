<?php
namespace App\Factories\Interfaces;

use App\Models\User;

interface UserFactoryInterface
{
    /**
     * @param array<string, mixed> $data
     * @return User
     */
    public static function create(array $data): User;
}