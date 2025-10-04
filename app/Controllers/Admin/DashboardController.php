<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Services\Interfaces\AppointmentReadServiceInterface;

class DashboardController extends Controller
{
    public function __construct(private readonly AppointmentReadServiceInterface $appointmentReadService)
    {

    }
    public function index()
    {
        try {
            $totalData = $this->appointmentReadService->getAppointmentsForDashboard();
            return $this->view('admin/dashboard', 
            [
                'appointments' => $totalData['appointments'], 
                'profit' => $totalData['profit'], 
                'mentors' => $totalData['mostActiveAndMostRatedMentors']
            ]);
        } catch (\Throwable $e) {
            $this->handleException($e);
            return $this->view('admin/dashboard');
        }
    }
}