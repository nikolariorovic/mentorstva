<?php

namespace App\Dto\Interfaces;

interface DtoInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}