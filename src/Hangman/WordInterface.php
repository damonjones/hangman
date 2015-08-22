<?php

namespace Hangman;

interface WordInterface
{
    /**
     * @param string $value
     *
     * @return WordInterface
     */
    static function fromString($value);

    /**
     * @return string
     */
    function value();

    /**
     * @return string
     */
    function __toString();

    /**
     * @param string $letter
     *
     * @return boolean
     */
    function contains($letter);

    /**
     * @param array $letters
     *
     * @return boolean
     */
    function canBeComposedWithLetters(array $letters);
}
