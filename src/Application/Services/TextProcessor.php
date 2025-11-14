<?php

namespace App\Application\Services;

use App\Domain\Models\TextResult;
use App\Infrastructure\Database\Database;

class TextProcessor
{
    public function transform(string $text): TextResult
    {
        $processed = strtoupper(trim($text));

        // split text into array of words
        $words = explode(' ', $text);
        
        // obtain first letter of each word
        $firstLetters = array_map(function ($word) {
            return $word[0].$word[1].'.';
        }, $words);
        
        // join first letters into a string
        $processed = implode('', $firstLetters);

        Database::execute(
            "INSERT INTO tb_acronymsHist (textOriginal, textProcessed) VALUES (?, ?)",
            [$text, $processed]
        );
        
        return new TextResult(
            original: $text,
            processed: $processed
        );
    }

    // Funtion to get acronym history
    public function getAcronymHistory(): array
    {
        $sql = "SELECT id, textOriginal, textProcessed FROM tb_acronymsHist ORDER BY id DESC";
        $stmt = Database::execute($sql);
        $history = [];
        while ($row = $stmt->fetch()) {
            $history[] = [
                'id' => $row['id'],
                'textOriginal' => $row['textOriginal'],
                'textProcessed' => $row['textProcessed']
            ];
        }
        return $history;
    }
}
