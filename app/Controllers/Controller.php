<?php
namespace App\Controllers;

use App\Exceptions\DatabaseException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\InvalidPaymentDataException;
use App\Exceptions\InvalidUserDataException;

class Controller {
    
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../../resources/view/' . $view . '.php';
    }
    
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function handleException(\Throwable $e, string $defaultMessage = 'Something went wrong.'): void
    {
        $error = match (true) {
            $e instanceof DatabaseException => [
                'message' => 'Something went wrong with database.',
                'log' => true,
            ],
            $e instanceof UserNotFoundException => [
                'message' => 'User not found.',
                'log' => false,
            ],
            $e instanceof InvalidArgumentException => [
                'message' => 'User not authenticated. Please login again.',
                'log' => false,
            ],
            $e instanceof InvalidPaymentDataException => [
                'message' => 'Validation failed',
                'log' => false,
            ],
            $e instanceof InvalidUserDataException => [
                'message' => 'Validation error. Errors: ' . implode(', ', $e->getErrors()),
                'log' => false,
            ],
            $e instanceof \Exception, $e instanceof \Error => [
                'message' => 'Error. Something went wrong.',
                'log' => true,
            ],
            default => [
                'message' => $defaultMessage,
                'log' => true,
            ],
        };

        $_SESSION['error'] = $error['message'];
    
        if ($error['log']) {
            logError($e->getMessage());
        }
    }
}