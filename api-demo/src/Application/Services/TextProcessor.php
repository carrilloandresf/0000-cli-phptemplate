<?php

namespace App\Application\Services;

use App\Domain\Models\TextResult;

class TextProcessor
{
    public function transform(string $text): TextResult
    {
        $processed = strtoupper(trim($text));
        $wordCount = count(array_filter(explode(' ', $text)));
        
        return new TextResult(
            original: $text,
            processed: $processed,
            length: strlen($processed),
            wordCount: $wordCount
        );
    }
}
