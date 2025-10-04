<?php
namespace App\Controllers;

use App\Services\Interfaces\AuthServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;

class LoginController extends Controller 
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {

    }

    public function login(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $user = $this->authService->attempt(email: $email, password: $password);
            $this->authService->login(user: $user);
            $this->redirect(url: '/');
        } catch (DatabaseException|UserNotFoundException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/');
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect(url: '/');
    }
}