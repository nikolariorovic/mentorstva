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

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $user = $this->authService->attempt($email, $password);
            $this->authService->login($user);
            $this->redirect('/');
        } catch (DatabaseException|UserNotFoundException|\Throwable $e) {
            $this->handleException($e);
            return $this->redirect('/');
        }
    }

    public function logout() {
        $this->authService->logout();
        $this->redirect('/');
    }
}