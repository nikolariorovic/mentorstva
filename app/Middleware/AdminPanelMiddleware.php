<?php
namespace App\Middleware;

use App\Enums\UserRole;
class AdminPanelMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::ADMIN && $_SESSION['user']['role'] !== UserRole::MENTOR) {
            header('Location: /');
            return false;
        }
        return true;
    }
} 