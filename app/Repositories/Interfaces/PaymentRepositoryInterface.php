<?php
namespace App\Repositories\Interfaces;

interface PaymentRepositoryInterface
{
    /**
     * @param array<string, mixed> $paymentData
     * @return int
     */
    public function savePayment(array $paymentData): int;
    public function updatePaymentStatus(int $paymentId, string $status): void;
    /**
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getPayments(int $page): array;
    public function paymentsAccepted(int $id): void;
}