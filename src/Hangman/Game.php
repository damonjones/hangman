<?php

namespace Hangman;

use Assert\Assertion as Assert;
use Assert\AssertionFailedException;
use Hangman\Exception\Game\InvalidGuessException;
use Hangman\Exception\Game\LetterAlreadyGuessedException;
use Hangman\Exception\Game\GameOverException;

class Game implements GameInterface
{
    const NUMBER_OF_GUESSES   = 11;
    const UNGUESSED_CHARACTER = '.';

    const BUSY    = 'busy';
    const FAIL    = 'fail';
    const SUCCESS = 'success';

    /**
     * @var WordInterface
     */
    protected $word;

    /**
     * @var array
     */
    protected $guessedLetters = [];

    /**
     * @var int
     */
    protected $guessesRemaining = self::NUMBER_OF_GUESSES;

    /**
     * @param WordInterface $word
     */
    private function __construct(WordInterface $word)
    {
        $this->word = $word;
    }

    /**
     * @param WordInterface $word
     *
     * @return GameInterface
     */
    public static function withWord(WordInterface $word)
    {
        return new static($word);
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
        $this->guardGameOver();

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
     * Get the word of the Game
     *
     * @return WordInterface
     */
    public function word()
    {
        return $this->word;
    }

    /**
     * @return string
     */
    public function obfuscatedWord()
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
     * @throws GameOverException
     */
    private function guardGameOver()
    {
        if (self::BUSY !== $this->status()) {
            throw new GameOverException('This game has already ended.');
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
