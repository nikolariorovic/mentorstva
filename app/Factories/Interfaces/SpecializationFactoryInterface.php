<?php
namespace App\Factories\Interfaces;

use App\Models\Specialization;

interface SpecializationFactoryInterface
{
    /**
     * @param array<string, mixed> $data
     * @return Specialization
     */
    public static function create(array $data): Specialization;
}