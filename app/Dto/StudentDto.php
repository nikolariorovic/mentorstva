<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;

readonly class StudentDto implements DtoInterface
{
    private function __construct(private array $payload)
    {

    }

    public static function fromIndex(array $data): self
    {
        return new self([
            'specializations' => $data
        ]);
    }

    public static function fromAppointments(array $data): self
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