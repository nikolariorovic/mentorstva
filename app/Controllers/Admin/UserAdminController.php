<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Repositories\UserRepository;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidUserDataException;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Validators\UserCreateValidator;
use App\Validators\UserUpdateValidator;
use App\Exceptions\UserNotFoundException;
use App\Services\Interfaces\SpecializationServiceInterface;
use App\Services\SpecializationService;
use App\Repositories\SpecializationRepository;

class UserAdminController extends Controller
{
    public function __construct(
        private readonly UserReadServiceInterface $userReadService,
        private readonly UserWriteServiceInterface $userWriteService,
        private readonly SpecializationServiceInterface $specializationService
    ) {

    }

    public function index()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
      
        try {
            $users = $this->userReadService->getPaginatedUsers($page);
            $specializations = $this->specializationService->getAllSpecializations();
            return $this->view('admin/index', ['users' => $users, 'specializations' => $specializations]);
        } catch (DatabaseException|InvalidUserDataException|\Throwable $e) {
            $this->handleException($e);
            return $this->view('admin/index');
        }
    }

    public function create()
    { 
        try {
            $this->userWriteService->createUser($_POST);
            $_SESSION['success'] = 'User created successfully';
            return $this->redirect('/admin/users');
        } catch (DatabaseException|InvalidUserDataException|\Throwable $e) {
            $this->handleException($e);
            return $this->redirect('/admin/users');
        }
    }

    public function show(int $id)
    { 
        try {
            $user = $this->userReadService->getUserById($id);
            $specializations = $this->specializationService->getAllSpecializations();
            return $this->view('admin/show', ['user' => $user, 'specializations' => $specializations]);
        } catch (DatabaseException|UserNotFoundException $e) {
            $this->handleException($e);
            return match (true) {
                $e instanceof DatabaseException => $this->view('admin/show', ['user' => null, 'specializations' => []]),
                $e instanceof UserNotFoundException => $this->redirect('/admin/users'),
                default => $this->redirect('/'),
            };
        }
    }

    public function update(int $id)
    {
        try {
            $this->userWriteService->updateUser($id, $_POST);
            $_SESSION['success'] = 'User updated successfully';
            return $this->redirect('/admin/users/' . $id);
        } catch (DatabaseException|InvalidUserDataException|UserNotFoundException|\Throwable $e) {
            $this->handleException($e);
            return match (true) {
                $e instanceof DatabaseException => $this->redirect('/admin/users/' . $id),
                $e instanceof InvalidUserDataException => $this->redirect('/admin/users/' . $id),
                $e instanceof UserNotFoundException => $this->redirect('/admin/users'),
                default => $this->redirect('/admin/users'),
            };
        }
    }

    public function delete(int $id)
    {
        try {
            $this->userWriteService->deleteUser($id);
            $_SESSION['success'] = 'User deleted successfully';
            return $this->redirect('/admin/users');
        } catch (DatabaseException|UserNotFoundException|\Throwable $e) {
            $this->handleException($e);
            return $this->redirect('/admin/users');
        }
    }
}