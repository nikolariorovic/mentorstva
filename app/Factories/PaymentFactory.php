<?php

declare(strict_types=1);

namespace App\Factories;

use App\Models\Payment;

final class PaymentFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): Payment
    {
        return new Payment(data: $data);
    }
} 