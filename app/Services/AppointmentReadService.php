<?php
namespace App\Services;

use App\Repositories\Interfaces\AppointmentReadRepositoryInterface;
use App\Validators\TimeSlotValidator;
use App\Helpers\AppointmentHelper;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use InvalidArgumentException;
use App\Services\Interfaces\SessionServiceInterface;

class AppointmentReadService implements AppointmentReadServiceInterface
{
    public function __construct(
        private readonly AppointmentReadRepositoryInterface $appointmentRepository,
        private readonly TimeSlotValidator $timeSlotValidator,
        private readonly SessionServiceInterface $sessionService
    ) {

    }

    /**
     * @param array<string, mixed> $data
     * @return list<array<string, string>>
     */
    public function getAvailableTimeSlots(array $data): array
    {
        $this->timeSlotValidator->validate(data: $data);
        
        $mentorId = (int) $data['mentor_id'];
        $date = $data['date'];
        
        $bookedSlots = $this->appointmentRepository->getAvailableTimeSlots(mentorId: $mentorId, date: $date);
        $allSlots = AppointmentHelper::getAllTimeSlotsForDate(date: $date);
        
        if (empty($bookedSlots)) {
            return $allSlots;
        }
        
        $bookedTimes = array_map(function($slot) {
            return $slot['period'];
        }, $bookedSlots);
        
        $availableSlots = array_filter($allSlots, function($slot) use ($bookedTimes) {
            return !in_array($slot['time'], $bookedTimes);
        });
        
        return array_values($availableSlots);
    }

    /**
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getPaginatedAppointments(int $page): array
    {
        $user = $this->sessionService->getSession();
        if (!$user || !$user['id']) {
            throw new InvalidArgumentException(message: 'User not authenticated. Please login again.');
        }
     
        return $this->appointmentRepository->getPaginatedAppointments(userId: $user['id'], role: $user['role'], page: $page);
    }

    /**
     * @return array<string, mixed>
     */
    public function getAppointmentsForDashboard(): array
    {
        $getAppointmentsForDashboard = $this->appointmentRepository->getAppointmentsForDashboard();
        $getSumOfProfit = $this->appointmentRepository->getSumOfProfit();
        $getMostActiveAndMostRatedMentors = $this->appointmentRepository->getMostActiveAndMostRatedMentors();
        return [
            'appointments' => $getAppointmentsForDashboard,
            'profit' => $getSumOfProfit,
            'mostActiveAndMostRatedMentors' => $getMostActiveAndMostRatedMentors
        ];
    }
}