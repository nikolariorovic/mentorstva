<?php
namespace App\Middleware;

use App\Enums\UserRole;
use App\Middleware\Interfaces\MiddlewareInterface;

class AdminPanelMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::ADMIN->value && $_SESSION['user']['role'] !== UserRole::MENTOR->value) {
            header('Location: /');
            return false;
        }
        return true;
    }
} 