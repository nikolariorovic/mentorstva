<?php
namespace App\Dto;

use App\Dto\Interfaces\DtoInterface;

readonly class PaymentDto implements DtoInterface
{
    private function __construct(private array $payload)
    {

    }

    public static function from(array $data): self
    {
        return new self([
            'payments' => $data
        ]);
    }

    public function toArray(): array
    {
        return $this->payload;
    }
}