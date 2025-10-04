<?php

namespace App\Controllers;

use App\Services\Interfaces\SpecializationServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidBookingDataException;
use App\Exceptions\InvalidTimeSlotDataException;
use App\Exceptions\InvalidArgumentException;
use App\Services\Interfaces\UserReadServiceInterface;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;

class StudentController extends Controller 
{
    public function __construct(
        private readonly SpecializationServiceInterface $specializationService,
        private readonly UserReadServiceInterface $userReadService,
        private readonly AppointmentReadServiceInterface $appointmentReadService,
        private readonly AppointmentWriteServiceInterface $appointmentWriteService
    ) {

    }

    public function index(): void
    {
        try {
            $specializations = $this->specializationService->getAllSpecializations();
            $this->view(view: 'student/index', data: ['specializations' => $specializations]);
        } catch (DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'student/index');
        }
    }

    public function getMentorBySpecialization(int $specializationId): void
    {
        try {
            $mentors = $this->userReadService->getMentorsBySpecialization(specializationId: $specializationId);
            $this->json(data: [
                'success' => true,
                'mentors' => $mentors
            ]);
        } catch (DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->json(data: [
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function getAvailableTimeSlots(): void
    {
        try {
            $slots = $this->appointmentReadService->getAvailableTimeSlots(data: $_GET);
            $this->json(data: [
                'success' => true,
                'slots' => $slots
            ]);
        } catch (InvalidTimeSlotDataException $e) {
            $this->json(data: json_decode((string) $e, true));
        } catch (DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->json(data: [
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function bookAppointment(): void
    {
        try {
            $this->appointmentWriteService->bookAppointment(data: $_POST);
            $this->json(data: [
                'success' => true,
                'message' => 'Appointment booked successfully'
            ]);
        } catch (InvalidBookingDataException $e) {
            $this->json(data: json_decode((string) $e, true));
        } catch (InvalidArgumentException|DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->json(data: [
                'success' => false,
                'message' => 'Error. Something went wrong'
            ]);
        }
    }

    public function appointments(): void
    {
        try {
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;

            $appointments = $this->appointmentReadService->getPaginatedAppointments(page: $page);
            $this->view(view: 'student/appointments',data: ['appointments' => $appointments]);
        } catch (DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'student/appointments');
        }
    }

    public function submitRating(): void
    {
        try {
            $this->appointmentWriteService->submitRating(data: $_POST);
            $this->json(data: [
                'success' => true,
                'message' => 'Rating submitted successfully'
            ]);
        } catch (InvalidArgumentException $e) {
            $this->json(data: json_decode((string) $e, true));
        } catch (DatabaseException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->json(data: json_decode((string) $e, true));
        }
    }
}