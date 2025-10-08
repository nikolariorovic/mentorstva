<?php

namespace App\Validators;

use App\Validators\Interfaces\ValidatorInterface;

abstract class BaseValidator implements ValidatorInterface
{
    /**
     * @var array<string, mixed> $errors
     */
    protected array $errors = [];
    /**
     * @var array<string, mixed> $rules
     */
    protected array $rules = [];

    public function __construct()
    {
        $this->setRules();
    }

    abstract protected function setRules(): void;

    /**
     * @param array<string, mixed> $data
     * @return void
     */
    public function validate(array $data): void
    {
        $this->errors = [];

        foreach ($this->rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $this->validateField(field: $field, value: $value, rules: $fieldRules);
        }

        if (!empty($this->errors)) {
            $this->throwValidationException();
        }
    }

    /**
     * @param string $field
     * @param string $value
     * @param array<string, mixed> $rules
     * @return void
     */
    protected function validateField(string $field, string $value, array $rules): void
    {
        // Required validation
        if (($rules['required'] ?? false) && empty($value)) {
            $this->errors[$field] = $rules['messages']['required'];
            return;
        }

        // Skip other validations if field is not required and empty
        if (empty($value) && !($rules['required'] ?? false)) {
            return;
        }

        // Min length validation
        if (isset($rules['min_length']) && strlen($value) < $rules['min_length']) {
            $this->errors[$field] = $rules['messages']['min_length'];
            return;
        }

        // Max length validation
        if (isset($rules['max_length']) && strlen($value) > $rules['max_length']) {
            $this->errors[$field] = $rules['messages']['max_length'];
            return;
        }

        // Email validation
        if (isset($rules['email']) && $rules['email'] && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $rules['messages']['email'];
            return;
        }

        // In array validation
        if (isset($rules['in']) && !in_array($value, $rules['in'])) {
            $this->errors[$field] = $rules['messages']['in'];
            return;
        }

        // Pattern validation
        if (isset($rules['pattern'])) {
            foreach ($rules['pattern'] as $patternName => $pattern) {
                if (!preg_match($pattern, $value)) {
                    $this->errors[$field] = $rules['messages'][$patternName];
                    return;
                }
            }
        }

        // Min value validation (for numeric fields)
        if (isset($rules['min']) && is_numeric($value) && $value < $rules['min']) {
            $this->errors[$field] = $rules['messages']['min'];
            return;
        }

        // Max value validation (for numeric fields)
        if (isset($rules['max']) && is_numeric($value) && $value > $rules['max']) {
            $this->errors[$field] = $rules['messages']['max'];
            return;
        }

        // Custom validation
        if (isset($rules['custom'])) {
            foreach ($rules['custom'] as $customRule) {
                $result = $customRule($value, []);
                if ($result !== true) {
                    $this->errors[$field] = $result;
                    return;
                }
            }
        }
    }

    abstract protected function throwValidationException(): void;
} 