<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;

readonly class MentorDto implements DtoInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    private function __construct(private array $payload)
    {

    }

    /**
     * @param list<array<string, mixed>> $data
     */
    public static function from(array $data): self
    {
        return new self([
            'appointments' => $data
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