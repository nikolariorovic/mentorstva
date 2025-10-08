<?php
namespace App\Services\Interfaces;

use App\Models\Specialization;

interface SpecializationServiceInterface
{
    /**
     * @return list<Specialization>
     */
    public function getAllSpecializations(): array;
}
