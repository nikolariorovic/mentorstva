<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
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
            $this->view(view: 'admin/dashboard', data:
            [
                'appointments' => $totalData['appointments'], 
                'profit' => $totalData['profit'], 
                'mentors' => $totalData['mostActiveAndMostRatedMentors']
            ]);
        } catch (\Throwable $e) {
            $this->handleException(e: $e);
            $this->view(view: 'admin/dashboard');
        }
    }
}