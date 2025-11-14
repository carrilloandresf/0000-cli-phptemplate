<?php

namespace App\Application\Services;

use App\Domain\Models\CalculationResult;

class CalculatorService
{
    public function process(string $expression): CalculationResult
    {
        if (empty($expression)) {
            throw new \InvalidArgumentException("Expression cannot be empty");
        }
        
        $parts = explode(' ', $expression);
        
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException("Use format: 'number operator number'");
        }
        
        [$a, $operator, $b] = $parts;
        
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \InvalidArgumentException("Both operands must be numbers");
        }
        
        $a = (float)$a;
        $b = (float)$b;
        
        $result = match($operator) {
            '+' => $a + $b,
            '-' => $a - $b,
            '*' => $a * $b,
            '/' => $b != 0 ? $a / $b : throw new \InvalidArgumentException("Division by zero"),
            default => throw new \InvalidArgumentException("Unsupported operator: $operator")
        };
        
        return new CalculationResult($result, $operator, [$a, $b]);
    }
}
