<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Dto\DashboardDto;
use App\Services\Interfaces\AppointmentReadServiceInterface;

class DashboardController extends Controller
{
    public function __construct(private readonly AppointmentReadServiceInterface $appointmentReadService)
    {

    }
    public function index(): void
    {
        try {
            $totalData = $this->appointmentReadService->getAppointmentsForDashboard();
            $this->view(view: 'admin/dashboard', data: DashboardDto::from(data: $totalData)->toArray());
        } catch (\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'admin/dashboard');
        }
    }
}