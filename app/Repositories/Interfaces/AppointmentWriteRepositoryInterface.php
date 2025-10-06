<?php
namespace App\Repositories\Interfaces;

interface AppointmentWriteRepositoryInterface {
    public function bookAppointment(int $mentorId, string $dateTime, int $studentId, float $price, int $specializationId): void;
    public function updateAppointmentStatus(int $appointmentId, string $status): void;
    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid): void;
    public function submitRating(int $appointmentId, int $rating, string $comment): void;
}