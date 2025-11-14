<?php

namespace App\Domain\Models;

class CalculationResult
{
    public function __construct(
        public readonly float $value,
        public readonly string $operation,
        public readonly array $inputs
    ) {}
    
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'operation' => $this->operation,
            'inputs' => $this->inputs,
            'success' => true
        ];
    }
}
