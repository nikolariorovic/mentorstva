<?php
namespace App\Services\Interfaces;

use App\Services\PaymentGateway\PaymentGatewayInterface;

interface PaymentProcessingServiceInterface
{
    /**
     * @param string|null $gatewayName
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function processPayment(string $gatewayName = null, array $data = []): array;
    public function registerGateway(PaymentGatewayInterface $gateway): void;
}