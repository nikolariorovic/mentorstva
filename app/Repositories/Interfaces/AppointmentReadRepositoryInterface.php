<?php
namespace App\Repositories\Interfaces;

interface AppointmentReadRepositoryInterface {
    /**
     * @param int $mentorId
     * @param string $date
     * @return list<array{period: string}>
     */
    public function getAvailableTimeSlots(int $mentorId, string $date): array;

    /**
     * @param int $userId
     * @param string $role
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getPaginatedAppointments(int $userId, string $role, int $page): array;
    /**
     * @return list<array<string, mixed>>
     */
    public function getAppointmentsForDashboard(): array;
    /**
     * @return list<array<string, mixed>>
     */
    public function getSumOfProfit(): array;
    /**
     * @return list<array<string, mixed>>
     */
    public function getMostActiveAndMostRatedMentors(): array;
}