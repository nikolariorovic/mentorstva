<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;

readonly class MentorDto implements DtoInterface
{
    private function __construct(private array $payload)
    {

    }

    public static function from(array $data): self
    {
        return new self([
            'appointments' => $data
        ]);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}