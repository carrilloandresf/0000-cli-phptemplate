<?php

namespace App\Domain\Models;

class TextResult
{
    public function __construct(
        public readonly string $original,
        public readonly string $processed,
        public readonly int $length,
        public readonly int $wordCount
    ) {}
    
    public function toArray(): array
    {
        return [
            'original' => $this->original,
            'processed' => $this->processed,
            'length' => $this->length,
            'word_count' => $this->wordCount,
            'success' => true
        ];
    }
}
