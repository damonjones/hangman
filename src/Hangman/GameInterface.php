<?php

namespace Hangman;

interface GameInterface
{
    /**
     * @param WordInterface $word
     *
     * @return GameInterface
     */
    static function withWord(WordInterface $word);

    /**
     * @return int
     */
    function guessesRemaining();

    /**
     * @param string $letter
     */
    function guess($letter);

    /**
     * @return string
     */
    function status();

    /**
     * @return WordInterface
     */
    function word();

    /**
     * @return string
     */
    function obfuscatedWord();
}
