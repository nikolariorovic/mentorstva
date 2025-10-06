<?php
namespace App\Models;

use App\Enums\UserRole;

class Mentor extends User
{
    /** @var Specialization[] */
    private array $specializations = [];

    public function __construct(array $data = [])
    {
        parent::__construct(data: $data);
        $this->setRole(role: UserRole::MENTOR->value);
        if (isset($data['price'])) {
            $this->setPrice(price: (float)$data['price']);
        }
    }

    /**
     * @return Specialization[]
     */
    public function getSpecializations(): array
    {
        return $this->specializations;
    }

    /**
     * @param Specialization[] $specializations
     */
    public function setSpecializations(array $specializations): void
    {
        $this->specializations = $specializations;
    }

    public function addSpecialization(Specialization $specialization): void
    {
        $this->specializations[$specialization->getId()] = $specialization;
    }

    public function toArray(): array
    {
        $specializationsArray = array_map(fn(Specialization $s) => $s->toArray(), $this->specializations);

        return array_merge(parent::toArray(), [
            'specializations' => array_values($specializationsArray)
        ]);
    }
} 