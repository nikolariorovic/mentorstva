<?php
namespace App\Services\Interfaces;

interface AppointmentReadServiceInterface {
    /**
     * @param array<string, mixed> $data
     * @return list<array<string, mixed>>
     */
    public function getAvailableTimeSlots(array $data): array;
    /**
     * @param int $page
     * @return list<array<string, mixed>>
     */
    public function getPaginatedAppointments(int $page): array;
    /**
     * @return array<string, mixed>
     */
    public function getAppointmentsForDashboard(): array;
}