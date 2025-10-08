<?php
namespace App\Services\Interfaces;

interface PaymentHistoryServiceInterface
{
    /**
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getPayments(int $page): array;
    public function paymentsAccepted(int $id): void;
}