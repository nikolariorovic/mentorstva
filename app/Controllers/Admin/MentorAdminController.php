<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\AppointmentReadServiceInterface;
use App\Services\Interfaces\AppointmentWriteServiceInterface;
use App\Exceptions\DatabaseException;
use App\Exceptions\InvalidArgumentException;

class MentorAdminController extends Controller
{
    private AppointmentReadServiceInterface $appointmentReadService;
    private AppointmentWriteServiceInterface $appointmentWriteService;

    public function __construct(
        AppointmentReadServiceInterface $appointmentReadService, 
        AppointmentWriteServiceInterface $appointmentWriteService
    ) {
        $this->appointmentReadService = $appointmentReadService;
        $this->appointmentWriteService = $appointmentWriteService;
    }
    public function index()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int) $_GET['page']
            : 1;
        
        try {
            $appointments = $this->appointmentReadService->getPaginatedAppointments($page);
            return $this->view('mentor/index', ['appointments' => $appointments]);
        } catch (DatabaseException|InvalidArgumentException|\Throwable $e) {
            $this->handleException($e);
            return $this->view('mentor/index');
        }
    }
    public function updateAppointmentStatus()
    {
        try {
            $this->appointmentWriteService->updateAppointmentStatus($_POST);
            return $this->json(['success' => true]);
        } catch (DatabaseException|InvalidArgumentException|\Throwable $e) {
            $this->handleException($e);
            return $this->json(['success' => false]);
        }
    }
}
