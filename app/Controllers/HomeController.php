<?php

namespace App\Controllers;

class HomeController extends Controller {
    
    public function index() {
        if (!isset($_SESSION['user'])) {
            $this->view('login');
            return;
        }

        $role = $_SESSION['user']['role'] ?? null;

        match ($role) {
            'student' => $this->redirect('/home'),
            'mentor' => $this->redirect('/admin/mentor'),
            'admin' => $this->redirect('/admin/users'),
            default => $this->view('login'),
        };        
    }
}