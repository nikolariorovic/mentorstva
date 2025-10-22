<?php
namespace App\Middleware;

use App\Enums\UserRole;
use App\Middleware\Interfaces\MiddlewareInterface;

class StudentMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::STUDENT->value) {
            header('Location: /');
            return false;
        }
        return true;
    }
}