<?php

namespace App\Controllers;

use App\Enums\UserRole;
class HomeController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            $this->view('login');
            return;
        }

        $role = $_SESSION['user']['role'] ?? null;

        match ($role) {
            UserRole::STUDENT => $this->redirect('/home'),
            UserRole::MENTOR => $this->redirect('/admin/mentor'),
            UserRole::ADMIN => $this->redirect('/admin/users'),
            default => $this->view('login'),
        };        
    }
}