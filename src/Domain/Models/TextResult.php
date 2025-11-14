<?php

namespace App\Domain\Models;

class TextResult
{
    public function __construct(
        public readonly string $original,
        public readonly string $processed
    ) {}
    
    public function toArray(): array
    {
        return [
            'original' => $this->original,
            'processed' => $this->processed
        ];
    }
}
