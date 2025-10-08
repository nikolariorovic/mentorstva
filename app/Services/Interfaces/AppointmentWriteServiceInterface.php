<?php
namespace App\Services\Interfaces;

interface AppointmentWriteServiceInterface {
    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function bookAppointment(array $data): void;
    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function updateAppointmentStatus(array $data): void;
    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void;
    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function submitRating(array $data): void;
}