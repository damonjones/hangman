<?php

namespace spec\Hangman;

use Hangman\Exception\Game\InvalidGuessException;
use Hangman\Exception\Game\LetterAlreadyGuessedException;
use Hangman\Exception\Game\TooManyGuessesException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Hangman\Game;
use Hangman\Word;

class GameSpec extends ObjectBehavior
{
    const VALID_WORD = 'alphabet';

    function let()
    {
        $this->beConstructedThrough('withWord', [Word::fromString(self::VALID_WORD)]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Game::class);
    }

    function it_should_return_the_number_of_guesses_remaining_when_constructed()
    {
        $this->guessesRemaining()->shouldReturn(Game::NUMBER_OF_GUESSES);
    }

    function it_should_decrement_the_number_of_guesses_remaining_after_guessing_an_invalid_letter()
    {
        $this->guess('z');
        $this->guessesRemaining()->shouldReturn(Game::NUMBER_OF_GUESSES - 1);
    }

    function it_should_not_decrement_the_number_of_guesses_remaining_after_guessing_a_valid_letter()
    {
        $this->guess('a');
        $this->guessesRemaining()->shouldReturn(Game::NUMBER_OF_GUESSES);
    }

    function it_should_throw_an_exception_when_guessing_a_letter_that_has_already_been_guessed()
    {
        $this->guess('a');
        $this->shouldThrow(LetterAlreadyGuessedException::class)->duringGuess('a');
    }

    function it_should_throw_an_exception_when_guessing_a_letter_that_has_already_been_guessed_in_a_different_case()
    {
        $this->guess('a');
        $this->shouldThrow(LetterAlreadyGuessedException::class)->duringGuess('A');
    }

    function it_should_throw_an_exception_if_the_letter_is_not_an_alpha_character()
    {
        $this->shouldThrow(InvalidGuessException::class)->duringGuess('?');
    }

    function it_should_throw_an_exception_when_guessing_a_string_of_more_than_one_character()
    {
        $this->shouldThrow(InvalidGuessException::class)->duringGuess('ab');
    }

    function it_should_throw_an_exception_when_guessing_a_letter_after_the_maximum_number_of_tries_has_been_reached()
    {
        $this->guessIncorrectlyTheMaximumNumberOfTimes();

        $this->shouldThrow(TooManyGuessesException::class)->duringGuess('z');
    }

    function it_should_return_a_busy_status_when_the_game_has_started()
    {
        $this->status()->shouldReturn(Game::BUSY);
    }

    function it_should_return_a_fail_status_when_the_game_has_failed()
    {
        $this->guessIncorrectlyTheMaximumNumberOfTimes();

        $this->status()->shouldReturn(Game::FAIL);
    }

    function it_should_return_a_success_status_when_the_game_has_been_won()
    {
        foreach (array_unique(str_split(self::VALID_WORD)) as $letter) {
            $this->guess($letter);
        }

        $this->status()->shouldReturn(Game::SUCCESS);
    }

    function it_should_be_able_to_represent_the_word_with_only_the_correct_guesses_shown()
    {
        $this->guess('a');
        $this->guess('e');
        $this->guess('z');

        $representation = implode('', [
            'a',
            Game::UNGUESSED_CHARACTER,
            Game::UNGUESSED_CHARACTER,
            Game::UNGUESSED_CHARACTER,
            'a',
            Game::UNGUESSED_CHARACTER,
            'e',
            Game::UNGUESSED_CHARACTER
        ]);

        $this->word()->shouldReturn($representation);
    }

    /**
     * Guess incorrectly the maximum number of times
     */
    private function guessIncorrectlyTheMaximumNumberOfTimes()
    {
        $invalidGuesses = array_values(array_diff(range('a', 'z'), str_split(self::VALID_WORD)));

        for ($try = 1; $try <= Game::NUMBER_OF_GUESSES; $try++) {
            $this->guess($invalidGuesses[$try]);
        }
    }
}
