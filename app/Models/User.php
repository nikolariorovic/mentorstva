<?php
namespace App\Models;

use DateTime;
use App\Exceptions\InvalidArgumentException;
use App\Enums\UserRole;

abstract class User
{
    protected ?int $id = null;
    protected string $first_name = '';
    protected string $last_name = '';
    protected string $email = '';
    protected string $password = '';
    protected string $biography = '';
    protected string $role = UserRole::STUDENT->value;
    protected ?float $price = null;
    protected DateTime $created_at;
    protected DateTime $updated_at;
    protected ?DateTime $deleted_at = null;

    /**
     * @throws \DateMalformedStringException
     * @throws InvalidArgumentException
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->id = $data['id'] ?? null;
            $this->setFirstName($data['first_name'] ?? '')
            ->setLastName($data['last_name'] ?? '')
            ->setEmail($data['email'] ?? '')
            ->setBiography($data['biography'] ?? '');
            $this->created_at = isset($data['created_at'])
                ? new DateTime($data['created_at']) 
                : new DateTime();
            $this->updated_at = isset($data['updated_at']) 
                ? new DateTime($data['updated_at']) 
                : new DateTime();
            $this->deleted_at = isset($data['deleted_at']) 
                ? new DateTime($data['deleted_at']) 
                : null;
            
            if (isset($data['password'])) {
                $this->setPassword($data['password']);
            }
        }
    }
    
    public function getId(): int 
    {
        return $this->id;
    }

    public function getFirstName(): string 
    {
        return $this->first_name;
    }

    public function getLastName(): string 
    {
        return $this->last_name;
    }

    public function getEmail(): string 
    {
        return $this->email;
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function getBiography(): string 
    {
        return $this->biography;
    }

    public function getRole(): string 
    {
        return $this->role;
    }

    public function getCreatedAt(): ?DateTime 
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTime 
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?DateTime 
    {
        return $this->deleted_at;
    }

    public function getFullName(): string 
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getPrice(): ?float 
    {
        return $this->price;
    }

    public function setFirstName(string $firstName): self
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException(message: 'First name cannot be empty');
        }
        $this->first_name = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        if (empty($lastName)) {
            throw new InvalidArgumentException(message: 'Last name cannot be empty');
        }
        $this->last_name = $lastName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(message: 'Invalid email format');
        }
        $this->email = $email;

        return $this;
    }

    public function setBiography(string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setRole(string $role): self
    {
        $allowedRoles = [UserRole::ADMIN->value, UserRole::MENTOR->value, UserRole::STUDENT->value];
        if (!in_array($role, $allowedRoles)) {
            throw new InvalidArgumentException(message: 'Invalid role. Must be one of: ' . implode(', ', $allowedRoles));
        }
        $this->role = $role;

        return $this;
    }

    public function setPrice(?float $price): self
    {
        if ($price !== null && $price < 0) {
            throw new InvalidArgumentException(message: 'Price cannot be negative');
        }
        $this->price = $price;

        return $this;
    }

    public function verifyPassword(string $password): bool 
    {
        return $password === $this->password;
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'biography' => $this->biography,
            'role' => $this->role,
            'price' => $this->price,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
} 