<?php
namespace App\Factories;

use App\Factories\Interfaces\UserFactoryInterface;
use App\Models\Admin;
use App\Models\Mentor;
use App\Models\Student;
use App\Models\User;
use App\Enums\UserRole;
use App\Exceptions\InvalidArgumentException;

class UserFactory implements UserFactoryInterface
{
    public static function create(array $data): User
    {
        return match($data['role']) {
            UserRole::ADMIN => new Admin($data),
            UserRole::MENTOR => new Mentor($data),
            UserRole::STUDENT => new Student($data),
            default => throw new InvalidArgumentException('Invalid user role')
        };
    }
}