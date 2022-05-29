<?php

namespace App\Support;

class WordFilterService
{
    private array $filterList = [];

    public function __construct()
    {
        $filterList = file_get_contents(
            resource_path('lang/word-filter.json')
        );

        $this->filterList = json_decode($filterList);
    }

    public function filterWords(string $sentence, string $replaceChar = '*'): string
    {
        $words = explode(' ', $sentence);

        foreach ($words as $i => $word) {
            if (!in_array(strtolower($word), $this->filterList)) continue;

            $words[$i] = str_repeat($replaceChar, strlen($word));
        }

        return join(' ', $words);
    }
}