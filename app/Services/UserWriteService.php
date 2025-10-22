<?php
namespace App\Services;

use App\Repositories\Interfaces\UserWriteRepositoryInterface;
use App\Repositories\Interfaces\UserReadRepositoryInterface;
use App\Repositories\Interfaces\UserSpecializationRepositoryInterface;
use App\Validators\Interfaces\ValidatorInterface;
use App\Services\Interfaces\UserWriteServiceInterface;
use App\Factories\UserFactory;
use App\Models\Mentor;
use App\Helpers\UserHelper;
use App\Exceptions\UserNotFoundException;

readonly class UserWriteService implements UserWriteServiceInterface
{
    public function __construct(
        private UserWriteRepositoryInterface          $userWriteRepository,
        private UserReadRepositoryInterface           $userReadRepository,
        private UserSpecializationRepositoryInterface $userSpecializationRepository,
        private ValidatorInterface                    $userCreateValidator,
        private ValidatorInterface                    $userUpdateValidator
    ) {

    }

    public function createUser(array $data): void
    {
        $this->userCreateValidator->validate(data: $data);
        $user = UserFactory::create(data: $data);
        
        $this->userWriteRepository->createUser(params: [
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getBiography() == '' ? null : $user->getBiography(),
            $user->getPrice() ?? 0.00,
            $user->getRole(),
            date('Y-m-d H:i:s')
        ]);

        if ($user instanceof Mentor && isset($data['specializations']) && is_array($data['specializations'])) {
            $userData = $this->userReadRepository->findByEmail(email: $user->getEmail());
            if ($userData) {
                $userId = $userData['id'];
                $specializationIds = array_map('intval', $data['specializations']);
                $this->userSpecializationRepository->saveUserSpecializations(userId: $userId, specializationIds: $specializationIds);
            }
        }
    }

    public function updateUser(int $id, array $data): void
    {
        $this->userUpdateValidator->validate(data: $data);

        $userData = $this->userReadRepository->getUserByIdOnly(id: $id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create(data: $userData);
        $isModified = false;

        if (isset($data['first_name']) && $user->getFirstName() !== $data['first_name']) {
            $user->setFirstName(firstName: $data['first_name']);
            $isModified = true;
        }
        if (isset($data['last_name']) && $user->getLastName() !== $data['last_name']) {
            $user->setLastName(lastName: $data['last_name']);
            $isModified = true;
        }
        if (isset($data['role']) && $user->getRole() !== $data['role']) {
            $user->setRole(role: $data['role']);
            $isModified = true;
        }
        if (array_key_exists('biography', $data) && $user->getBiography() !== $data['biography']) {
            $user->setBiography(biography: $data['biography']);
            $isModified = true;
        }
        
        $newPrice = ($data['price'] !== '' && $data['price'] !== null) ? (float)$data['price'] : 0.00;
        if ($user->getPrice() !== $newPrice) {
            $user->setPrice(price: $newPrice);
            $isModified = true;
        }

        if ($isModified) {
            $this->userWriteRepository->updateUser(params: [
                $user->getFirstName(),
                $user->getLastName(),
                $user->getRole(),
                $user->getBiography() == '' ? null : $user->getBiography(),
                $user->getPrice(),
                date('Y-m-d H:i:s'),
                $user->getId()
            ]);
        }

        if ($user instanceof Mentor) {
            if (isset($data['specializations']) && is_array($data['specializations'])) {
                $specializationIds = array_map('intval', $data['specializations']);
                
                $specializationsData = $this->userSpecializationRepository->getUserSpecializations(id: $id);
                $specializations = UserHelper::setSpecializations(userSqlData: $specializationsData);
                $user->setSpecializations(specializations: $specializations);

                $currentSpecializationIds = array_map(fn($s) => $s->getId(), $user->getSpecializations());
                
                $specializationsChanged = count($specializationIds) !== count($currentSpecializationIds) || 
                                        array_diff($specializationIds, $currentSpecializationIds) !== [] ||
                                        array_diff($currentSpecializationIds, $specializationIds) !== [];
                
                if ($specializationsChanged) {
                    $this->userSpecializationRepository->deleteUserSpecializations(userId: $user->getId());
                    $this->userSpecializationRepository->saveUserSpecializations(userId: $user->getId(), specializationIds: $specializationIds);
                }
            } else {
                $this->userSpecializationRepository->deleteUserSpecializations(userId: $user->getId());
            }
        }
    }

    public function deleteUser(int $id): void
    {
        $userData = $this->userReadRepository->getUserByIdOnly(id: $id);
        if (!$userData) throw new UserNotFoundException();
        
        $user = UserFactory::create(data: $userData);
        
        if ($user instanceof Mentor) {
            $this->userSpecializationRepository->deleteUserSpecializations(userId: $user->getId());
        }
        
        $this->userWriteRepository->deleteUser(params: [
            date('Y-m-d H:i:s'),
            $user->getEmail() . '_deleted_' . time(),
            $user->getId()
        ]);
    }
}
