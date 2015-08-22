<?php

namespace Hangman;

use Assert\Assertion as Assert;
use Assert\AssertionFailedException;
use Hangman\Exception\Word\InvalidLengthException;
use Hangman\Exception\Word\InvalidCharactersException;

class Word implements WordInterface
{
    const MINIMUM_LENGTH = 3;
    const MAXIMUM_LENGTH = 11;

    /** @var string */
    protected $value;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        $value = strtolower($value);

        $this->validateLength($value);
        $this->validateCharacters($value);

        $this->value = $value;
    }

    /**
     * @param string $value
     *
     * @return Word
     */
    public static function fromString($value)
    {
        return new static($value);
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value();
    }

    /**
     * @param string $letter
     *
     * @return boolean
     */
    public function contains($letter)
    {
        return in_array($letter, str_split($this->value));
    }

    /**
     * @param array $letters
     *
     * @return boolean
     */
    public function canBeComposedWithLetters(array $letters)
    {
        return 0 === count(array_diff(str_split($this->value), $letters));
    }

    /**
     * @param string $value
     */
    private function validateLength($value)
    {
        try {
            Assert::minLength($value, self::MINIMUM_LENGTH);
            Assert::maxLength($value, self::MAXIMUM_LENGTH);
        } catch (AssertionFailedException $e) {
            throw new InvalidLengthException(sprintf(
                'The word must be between %d and %d characters long.',
                self::MINIMUM_LENGTH,
                self::MAXIMUM_LENGTH
            ));
        }
    }

    /**
     * @param string $value
     */
    private function validateCharacters($value)
    {
        try {
            Assert::regex($value, '/^[a-z]*$/i');
        } catch (AssertionFailedException $e) {
            throw new InvalidCharactersException('The word can only contain the characters a-z.');
        }
    }
}
