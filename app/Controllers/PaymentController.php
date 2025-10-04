<?php
namespace App\Controllers;

use App\Dto\PaymentDto;
use App\Services\PaymentService;
use App\Exceptions\InvalidPaymentDataException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\DatabaseException;

class PaymentController extends Controller 
{
    public function __construct(private readonly PaymentService $paymentService)
    {

    }

    public function processPayment(): void
    {
        try {
            $gateway = $_POST['gateway'] ?? null;
            
            $paymentData = $_POST;
            unset($paymentData['gateway']);
            
            if ($gateway !== null && !$this->paymentService->isGatewayAvailable(gatewayName: $gateway)) {
                $_SESSION['error'] = 'Invalid payment gateway';
                $this->redirect(url: '/appointments');
            }
    
            $result = $this->paymentService->processPayment(gatewayName: $gateway, data: $paymentData);
            
            if ($result['success']) {
                $_SESSION['success'] = 'Payment processed successfully!';
            } else {
                $_SESSION['error'] = 'Payment failed: ' . ($result['message'] ?? 'Unknown error');
            }
            
            $this->redirect(url: '/appointments');
            
        } catch (InvalidArgumentException|InvalidPaymentDataException|\Exception $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/appointments');
        }
    }

    public function getPayments()
    {
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
        ? (int) $_GET['page']
        : 1;

        try {
            $payments = $this->paymentService->getPayments(page: $page);
            $this->view(view: 'admin/payments', data: PaymentDto::from($payments)->toArray());
        } catch (DatabaseException|\Exception $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/admin/payments');
        }
    }

    public function paymentsAccepted($id): void
    {
        try {
            $this->paymentService->paymentsAccepted(id: $id);
            $this->redirect(url: '/admin/payments');
        } catch (DatabaseException|\Exception $e) {
            $this->handleException(e: $e);
            $this->redirect(url: '/admin/payments');
        }
    }
}