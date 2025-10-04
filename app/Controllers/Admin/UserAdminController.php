<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Dto\UserAdminDto;
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

    public function index(): void
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
      
        try {
            $users = $this->userReadService->getPaginatedUsers(page: $page);
            $specializations = $this->specializationService->getAllSpecializations();
            $this->view(view: 'admin/index', data: UserAdminDto::fromIndex(users: $users, specializations: $specializations)->toArray());
        } catch (DatabaseException|InvalidUserDataException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'admin/index');
        }
    }

    public function create(): void
    { 
        try {
            $this->userWriteService->createUser(data: $_POST);
            $_SESSION['success'] = 'User created successfully';
            $this->redirect(url: '/admin/users');
        } catch (DatabaseException|InvalidUserDataException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/admin/users');
        }
    }

    public function show(int $id): void
    { 
        try {
            $user = $this->userReadService->getUserById(id: $id);
            $specializations = $this->specializationService->getAllSpecializations();
            $this->view(view: 'admin/show', data: UserAdminDto::fromShow(user: $user, specializations: $specializations)->toArray());
        } catch (DatabaseException|UserNotFoundException $e) {
            $this->handleException(e: $e);
            match (true) {
                $e instanceof DatabaseException => $this->view(view: 'admin/show', data: UserAdminDto::fromShow(user: null, specializations: [])->toArray()),
                $e instanceof UserNotFoundException => $this->redirect(url: '/admin/users'),
                default => $this->redirect(url: '/'),
            };
        }
    }

    public function update(int $id): void
    {
        try {
            $this->userWriteService->updateUser(id: $id, data: $_POST);
            $_SESSION['success'] = 'User updated successfully';
            $this->redirect(url: '/admin/users/' . $id);
        } catch (DatabaseException|InvalidUserDataException|UserNotFoundException|\Throwable $e) {
            $this->handleException(e: $e);
            match (true) {
                $e instanceof DatabaseException, $e instanceof InvalidUserDataException => $this->redirect(url: '/admin/users/' . $id),
                default => $this->redirect(url: '/admin/users'),
            };
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->userWriteService->deleteUser(id: $id);
            $_SESSION['success'] = 'User deleted successfully';
            $this->redirect(url: '/admin/users');
        } catch (DatabaseException|UserNotFoundException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/admin/users');
        }
    }
}