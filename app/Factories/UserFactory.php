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
            UserRole::ADMIN->value => new Admin(data: $data),
            UserRole::MENTOR->value => new Mentor(data: $data),
            UserRole::STUDENT->value => new Student(data: $data),
            default => throw new InvalidArgumentException(message: 'Invalid user role')
        };
    }
}