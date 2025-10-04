<?php
namespace App\Middleware;

use App\Enums\UserRole;
class StudentMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::STUDENT) {
            header('Location: /');
            return false;
        }
    }
}