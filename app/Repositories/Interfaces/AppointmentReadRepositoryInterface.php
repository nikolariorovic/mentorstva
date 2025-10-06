<?php
namespace App\Repositories\Interfaces;

interface AppointmentReadRepositoryInterface {
    public function getAvailableTimeSlots(int $mentorId, string $date): array;
    public function getPaginatedAppointments(int $userId, string $role, int $page): array;
    public function getAppointmentsForDashboard(): array;
    public function getSumOfProfit(): array;
    public function getMostActiveAndMostRatedMentors(): array;
}