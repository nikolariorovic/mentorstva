<?php
namespace App\Exceptions;

class InvalidArgumentException extends BaseException
{
    public function __construct(string $message = "Invalid argument", int $code = 400)
    {
        parent::__construct(message: $message, code: $code);
    }

    public function __toString(): string
    {
        $response = [
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->getErrors(),
            'logout' => true
        ];

        $json = json_encode($response);

        if ($json === false) {
            $error = json_last_error_msg();
            return '{"success":false,"message":"JSON encode failed: ' . addslashes($error) . '"}';
        }

        return $json;
    }
}