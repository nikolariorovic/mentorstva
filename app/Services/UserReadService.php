<?php
namespace App\Services;

use App\Exceptions\InvalidArgumentException;
use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;
use App\Factories\UserFactory;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Models\User;
use App\Models\Mentor;
use App\Helpers\UserHelper;
use App\Exceptions\UserNotFoundException;

readonly class UserReadService implements UserReadServiceInterface
{
    public function __construct(
        private UserReadRepositoryInterface           $userReadRepository,
        private UserSpecializationRepositoryInterface $userSpecializationRepository
    ) {

    }

    /**
     * @param int $page
     * @return list<User> $data
     * @throws InvalidArgumentException
     */
    public function getPaginatedUsers(int $page): array
    {
        $users = $this->userReadRepository->getAllUsers(page: $page);
        return array_map(fn($userData) => UserFactory::create(data: $userData), $users);
    }

    public function getUserById(int $id): ?User
    {
        $userData = $this->userReadRepository->getUserByIdOnly(id: $id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create(data: $userData);

        if ($user instanceof Mentor) {
            $specializationsData = $this->userSpecializationRepository->getUserSpecializations(id: $id);
            $specializations = UserHelper::setSpecializations(userSqlData: $specializationsData);
            $user->setSpecializations(specializations: $specializations);
        }
        
        return $user;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getMentorsBySpecialization(int $specializationId): array
    {
        return $this->userReadRepository->getMentorsBySpecialization(specializationId: $specializationId);
    }
}
