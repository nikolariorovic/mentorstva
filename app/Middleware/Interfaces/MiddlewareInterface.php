<?php
namespace App\Middleware\Interfaces;

interface MiddlewareInterface {
    /**
     * @return bool
     */
    public function handle(): bool;
}