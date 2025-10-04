<?php
namespace App\Controllers;

use App\Services\PaymentService;
use App\Exceptions\InvalidPaymentDataException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\DatabaseException;

class PaymentController extends Controller 
{
    public function __construct(private readonly PaymentService $paymentService)
    {

    }

    public function processPayment()
    {
        try {
            $gateway = $_POST['gateway'] ?? null;
            
            $paymentData = $_POST;
            unset($paymentData['gateway']);
            
            if ($gateway !== null && !$this->paymentService->isGatewayAvailable($gateway)) {
                $_SESSION['error'] = 'Invalid payment gateway';
                return $this->redirect('/appointments');
            }
    
            $result = $this->paymentService->processPayment($gateway, $paymentData);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Payment processed successfully!';
            } else {
                $_SESSION['error'] = 'Payment failed: ' . ($result['message'] ?? 'Unknown error');
            }
            
            return $this->redirect('/appointments');
            
        } catch (InvalidArgumentException|InvalidPaymentDataException|\Exception $e) {
            $this->handleException($e);
            return $this->redirect('/appointments');
        }
    }

    public function getPayments()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
        ? (int) $_GET['page']
        : 1;

        try {
            $payments = $this->paymentService->getPayments($page);
            return $this->view('admin/payments', ['payments' => $payments]);
        } catch (DatabaseException|\Exception $e) {
            $this->handleException($e);
            return $this->redirect('/admin/payments');
        }
    }

    public function paymentsAccepted($id)
    {
        try {
            $payments = $this->paymentService->paymentsAccepted($id);
            return $this->redirect('/admin/payments');
        } catch (DatabaseException|\Exception $e) {
            $this->handleException($e);
            return $this->redirect('/admin/payments');
        }
    }
}