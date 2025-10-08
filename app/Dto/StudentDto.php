<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;
use App\Models\Specialization;

readonly class StudentDto implements DtoInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    private function __construct(private array $payload)
    {

    }

    /**
     * @param list<Specialization> $data
     */
    public static function fromIndex(array $data): self
    {
        return new self([
            'specializations' => $data
        ]);
    }

    /**
     * @param list<array<string, mixed>> $data
     */
    public static function fromAppointments(array $data): self
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