<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;

readonly class DashboardDto implements DtoInterface
{
    private function __construct(private array $payload)
    {

    }

    public static function from(array $data): self
    {
        return new self([
            'appointments' => $data['appointments'],
            'profit' => $data['profit'],
            'mentors' => $data['mostActiveAndMostRatedMentors'],
        ]);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}