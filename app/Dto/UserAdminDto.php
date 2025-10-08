<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;
use App\Models\Specialization;
use App\Models\User;

readonly class UserAdminDto implements DtoInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    private function __construct(private array $payload)
    {

    }

    /**
     * @param list<Specialization> $specializations
     */
    public static function fromShow(?User $user, array $specializations): self
    {
        return new self([
            'user' => $user,
            'specializations' => $specializations,
        ]);
    }

    /**
     * @param list<Specialization> $specializations
     * @param list<User> $users
     */
    public static function fromIndex(array $users, array $specializations): self
    {
        return new self([
            'users' => $users,
            'specializations' => $specializations,
        ]);
    }

    /**
     * @return  array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}