<?php

namespace Hangman;

use Assert\Assertion as Assert;
use Assert\AssertionFailedException;

use Hangman\Exception\Game\InvalidGuessException;
use Hangman\Exception\Game\LetterAlreadyGuessedException;
use Hangman\Exception\Game\TooManyGuessesException;

final class Game
{
    const NUMBER_OF_GUESSES = 11;
    const UNGUESSED_CHARACTER = '.';

    const BUSY = 'busy';
    const FAIL = 'fail';
    const SUCCESS = 'success';

    /**
     * @var Word
     */
    private $word;

    /**
     * @var array
     */
    private $guessedLetters = [];

    /**
     * @var int
     */
    private $guessesRemaining = self::NUMBER_OF_GUESSES;

    /**
     * @param string $word
     */
    private function __construct($word)
    {
        $this->word = $word;
    }

    /**
     * @param string $word
     *
     * @return Game
     */
    public static function withWord($word)
    {
        return new Game($word);
    }

    /**
     * @return int
     */
    public function guessesRemaining()
    {
        return $this->guessesRemaining;
    }

    /**
     * @param string $letter
     */
    public function guess($letter)
    {
        $this->guardTooManyGuesses();

        $letter = strtolower($letter);

        $this->validateGuess($letter);
        $this->addGuess($letter);
    }

    /**
     * @return string
     */
    public function status()
    {
        if ($this->word->canBeComposedWithLetters($this->guessedLetters)) {
            return self::SUCCESS;
        }

        if (!$this->guessesRemaining()) {
            return self::FAIL;
        }

        return self::BUSY;
    }

    /**
     * @return string
     */
    public function word()
    {
        return str_replace(            // Replace
            array_diff(                // the unguessed letters - which is the difference of
                range('a', 'z'),       // the letters in the alphabet and
                $this->guessedLetters  // the guessed letters
            ),
            self::UNGUESSED_CHARACTER, // with the unguessed character
            $this->word                // in the word
        );
    }

    /**
     * @throws TooManyGuessesException
     */
    private function guardTooManyGuesses()
    {
        if (!$this->guessesRemaining()) {
            throw new TooManyGuessesException('You have already reached the maximum number of guesses.');
        }
    }

    /**
     * @param string $letter
     *
     * @throws InvalidGuessException
     * @throws LetterAlreadyGuessedException
     */
    private function validateGuess($letter)
    {
        try {
            Assert::regex($letter, '/^[a-z]$/i');
        } catch (AssertionFailedException $e) {
            throw new InvalidGuessException('The guessed letter must be a-z');
        }

        if (in_array($letter, $this->guessedLetters)) {
            throw new LetterAlreadyGuessedException(sprintf('You have already guessed the letter \'%s\'', $letter));
        }
    }

    /**
     * @param string $letter
     */
    private function addGuess($letter)
    {
        $this->guessedLetters[] = $letter;

        if (!$this->word->contains($letter)) {
            $this->guessesRemaining--;
        }
    }
}
