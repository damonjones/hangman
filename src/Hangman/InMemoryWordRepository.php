<?php

namespace Hangman;

final class InMemoryWordRepository implements WordRepositoryInterface
{
    /**
     * @var array
     */
    private $words;

    /**
     * InMemoryWordRepository constructor.
     *
     * @param array $words
     */
    public function __construct(array $words)
    {
        $this->words = $words;
    }

    /**
     * @return WordInterface
     */
    public function pickARandomWord()
    {
        return Word::fromString($this->words[array_rand($this->words, 1)]);
    }
}
