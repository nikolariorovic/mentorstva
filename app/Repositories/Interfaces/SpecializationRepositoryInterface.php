<?php
namespace App\Repositories\Interfaces;

interface SpecializationRepositoryInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function getAll(): array;
} 