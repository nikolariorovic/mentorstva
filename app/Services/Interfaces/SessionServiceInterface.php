<?php
namespace App\Services\Interfaces;

interface SessionServiceInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getSession(): ?array;
}