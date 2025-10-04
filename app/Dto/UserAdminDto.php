<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;
use App\Models\User;

readonly class UserAdminDto implements DtoInterface
{
    private function __construct(private array $payload)
    {

    }

    public static function fromShow(?User $user, array $specializations): self
    {
        return new self([
            'user' => $user,
            'specializations' => $specializations,
        ]);
    }
    public static function fromIndex(array $users, array $specializations): self
    {
        return new self([
            'users' => $users,
            'specializations' => $specializations,
        ]);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}