<?php
namespace App\Validators\Interfaces;

interface ValidatorInterface
{
    /**
     * @param  array<string, mixed> $data
     */
    public function validate(array $data): void;
}