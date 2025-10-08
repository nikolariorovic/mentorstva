<?php

declare(strict_types=1);

namespace App\Services\PaymentGateway;

interface PaymentGatewayInterface
{
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function charge(array $data): array;

    public function getName(): string;

    public function getDisplayName(): string;
} 