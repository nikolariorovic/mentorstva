<?php
namespace App\Models;

use App\Enums\UserRole;

class Student extends User
{
    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->setRole(role: UserRole::STUDENT->value);
    }
} 