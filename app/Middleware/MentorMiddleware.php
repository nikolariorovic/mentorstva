<?php
namespace App\Middleware;

use App\Enums\UserRole;
class MentorMiddleware
{
    public function handle(): bool
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::MENTOR->value) {
            header('Location: /');
            return false;
        }
        return true;
    }
}