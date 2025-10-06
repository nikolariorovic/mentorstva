<?php
namespace App\Services;

use App\Repositories\Interfaces\AppointmentWriteRepositoryInterface;
use App\Validators\BookingValidator;
use App\Validators\UpdateAppointmentStatusValidator;
use App\Validators\RatingValidator;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Services\Interfaces\SessionServiceInterface;

class AppointmentWriteService implements AppointmentWriteServiceInterface
{
    public function __construct(
        private readonly AppointmentWriteRepositoryInterface $appointmentRepository,
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
            throw new \InvalidArgumentException(message: 'User not authenticated. Please login again.');
        }

        $this->bookingValidator->validate(data: $data);
        
        $dateTime = strpos($data['time'], ' ') !== false ? $data['time'] : $data['date'] . ' ' . $data['time'];
        
        $this->appointmentRepository->bookAppointment(mentorId: $data['mentor_id'], dateTime: $dateTime, studentId: $user['id'], price: $data['price'], specializationId: $data['specialization_id']);
    }

    public function updateAppointmentStatus(array $data): void
    {
        $this->updateAppointmentStatusValidator->validate(data: $data);
        $this->appointmentRepository->updateAppointmentStatus(appointmentId: $data['appointment_id'], status: $data['status']);
    }

    public function updatePaymentStatus(int $appointmentId, string $paymentStatus, bool $isPaid = false): void
    {
        $this->appointmentRepository->updatePaymentStatus(appointmentId: $appointmentId, paymentStatus: $paymentStatus, isPaid: $isPaid);
    }

    public function submitRating(array $data): void
    {
        $this->ratingValidator->validate(data: $data);
        $this->appointmentRepository->submitRating(appointmentId: $data['appointment_id'], rating: $data['rating'], comment: $data['comment']);
    }
}