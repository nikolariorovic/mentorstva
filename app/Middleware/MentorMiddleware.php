<?php
namespace App\Middleware;

use App\Enums\UserRole;
class MentorMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::MENTOR) {
            header('Location: /');
            return false;
        }
    }
}