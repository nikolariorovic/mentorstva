<?php
namespace App\Services\Interfaces;

interface PaymentGatewayManagementServiceInterface
{
    public function isGatewayAvailable(string $gatewayName): bool;
    /**
     * @return list<string>
     */
    public function getAvailableGateways(): array;
}