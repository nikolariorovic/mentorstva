<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Dto\MentorDto;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidArgumentException;

class MentorAdminController extends Controller
{
    public function __construct(
        private readonly AppointmentReadServiceInterface $appointmentReadService,
        private readonly AppointmentWriteServiceInterface $appointmentWriteService
    ) {

    }
    public function index(): void
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
        
        try {
            $appointments = $this->appointmentReadService->getPaginatedAppointments(page: $page);
            $this->view(view: 'mentor/index', data: MentorDto::from(data: $appointments)->toArray());
        } catch (DatabaseException|InvalidArgumentException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'mentor/index');
        }
    }
    public function updateAppointmentStatus(): void
    {
        try {
            $this->appointmentWriteService->updateAppointmentStatus(data: $_POST);
            $this->json(data: ['success' => true]);
        } catch (DatabaseException|InvalidArgumentException|\Throwable $e) {
            $this->handleException(e: $e);
            $this->json(data: ['success' => false]);
        }
    }
}
