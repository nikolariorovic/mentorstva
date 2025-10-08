<?php

namespace App\Models;

use DateTime;
use App\Exceptions\InvalidArgumentException;

class Payment
{
    protected int $appointment_id = 0;
    protected int $student_id = 0;
    protected float $price = 0.0;
    protected string $status = 'pending';
    protected string $method = '';
    protected string $transaction_id = '';
    protected string $card_number = '';
    protected DateTime $created_at;

    /**
     * @param array<string, mixed> $data
     * @throws InvalidArgumentException
     * @throws \DateMalformedStringException
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->setAppointmentId($data['appointment_id'] ?? 0)
            ->setStudentId($data['student_id'] ?? 0)
            ->setPrice($data['price'] ?? 0.0)
            ->setStatus($data['status'] ?? 'pending')
            ->setMethod($data['method'] ?? '')
            ->setTransactionId($data['transaction_id'] ?? '')
            ->setCardNumber($data['card_number'] ?? '');
            $this->created_at = isset($data['created_at']) 
                ? new DateTime($data['created_at']) 
                : new DateTime();
        }
    }

    public function getAppointmentId(): int
    {
        return $this->appointment_id;
    }

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function getCardNumber(): string
    {
        return $this->card_number;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setAppointmentId(int $appointmentId): self
    {
        if ($appointmentId <= 0) {
            throw new InvalidArgumentException(message: 'Appointment ID must be positive');
        }
        $this->appointment_id = $appointmentId;

        return $this;
    }

    public function setStudentId(int $studentId): self
    {
        if ($studentId <= 0) {
            throw new InvalidArgumentException(message: 'Student ID must be positive');
        }
        $this->student_id = $studentId;

        return $this;
    }

    public function setPrice(float $price): self
    {
        if ($price <= 0) {
            throw new InvalidArgumentException(message: 'Price cannot be negative');
        }
        $this->price = $price;

        return $this;
    }

    public function setStatus(string $status): self
    {
        $allowedStatuses = ['pending', 'confirmed','failed'];
        if (!in_array($status, $allowedStatuses)) {
            throw new InvalidArgumentException(message: 'Invalid status. Must be one of: ' . implode(', ', $allowedStatuses));
        }
        $this->status = $status;

        return $this;
    }

    public function setMethod(string $method): self
    {
        if (empty(trim($method))) {
            throw new InvalidArgumentException(message: 'Invalid payment method.');
        }
        $this->method = $method;

        return $this;
    }

    public function setTransactionId(string $transactionId): self
    {
        if (empty(trim($transactionId))) {
            throw new InvalidArgumentException(message: 'Transaction ID cannot be empty');
        }
        $this->transaction_id = $transactionId;

        return $this;
    }

    public function setCardNumber(string $cardNumber): self
    {
        if (empty(trim($cardNumber))) {
            throw new InvalidArgumentException(message: 'Card number cannot be empty');
        }
        $this->card_number = $cardNumber;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'appointment_id' => $this->appointment_id,
            'student_id' => $this->student_id,
            'price' => $this->price,
            'status' => $this->status,
            'method' => $this->method,
            'transaction_id' => $this->transaction_id,
            'card_number' => $this->card_number,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}