<?php
namespace App\Models;

use App\Enums\UserRole;

class Admin extends User
{
    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->setRole(role: UserRole::ADMIN->value);
    }
} 