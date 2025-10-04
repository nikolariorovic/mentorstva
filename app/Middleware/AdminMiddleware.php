<?php
namespace App\Middleware;

use App\Enums\UserRole;
class AdminMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== UserRole::ADMIN) {
            header('Location: /');
            return false;
        }
    }
}