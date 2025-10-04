<?php

namespace App\Controllers;

use App\Enums\UserRole;
class HomeController extends Controller {
    
    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->view(view: 'login');
            return;
        }

        $role = $_SESSION['user']['role'] ?? null;

        match ($role) {
            UserRole::STUDENT->value => $this->redirect(url: '/home'),
            UserRole::MENTOR->value => $this->redirect(url: '/admin/mentor'),
            UserRole::ADMIN->value => $this->redirect(url: '/admin/users'),
            default => $this->view(view: 'login'),
        };        
    }
}