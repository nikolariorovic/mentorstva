<?php
namespace App\Services;

use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use App\Validators\BookingValidator;
use App\Validators\UpdateAppointmentStatusValidator;
use App\Validators\RatingValidator;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Services\Interfaces\SessionServiceInterface;

class AppointmentWriteService implements AppointmentWriteServiceInterface
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointmentRepository,
        private readonly BookingValidator $bookingValidator,
        private readonly UpdateAppointmentStatusValidator $updateAppointmentStatusValidator,
        private readonly RatingValidator $ratingValidator,
        private readonly SessionServiceInterface $sessionService
    ) {

    }

    public function bookAppointment(array $data): void
    {
        $user = $this->sessionService->getSession();
        if (!$user || !$user['id']) {
            throw new \InvalidArgumentException('User not authenticated. Please login again.');
        }

        $this->bookingValidator->validate($data);
        
        $dateTime = strpos($data['time'], ' ') !== false ? $data['time'] : $data['date'] . ' ' . $data['time'];
        
        $this->appointmentRepository->bookAppointment($data['mentor_id'], $dateTime, $user['id'], $data['price'], $data['specialization_id']);
    }

    public function updateAppointmentStatus(array $data): void
    {
        $this->updateAppointmentStatusValidator->validate($data);
        $this->appointmentRepository->updateAppointmentStatus($data['appointment_id'], $data['status']);
    }

    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void
    {
        $this->appointmentRepository->updatePaymentStatus($appointmentId, $paymentStatus, $isPaid);
    }

    public function submitRating(array $data): void
    {
        $this->ratingValidator->validate($data);
        $this->appointmentRepository->submitRating($data['appointment_id'], $data['rating'], $data['comment']);
    }
}