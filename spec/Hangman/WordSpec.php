<?php

namespace spec\Hangman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Hangman\Word;

use Hangman\Exception\Word\InvalidCharactersException;
use Hangman\Exception\Word\InvalidLengthException;

class WordSpec extends ObjectBehavior
{
    const VALID_WORD = 'Alphabet';

    function let()
    {
        $this->beConstructedThrough('fromString', [self::VALID_WORD]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Word::class);
    }

    function it_should_throw_an_exception_when_constructed_with_null()
    {
        $this->beConstructedThrough('fromString', [null]);
        $this->shouldThrow(InvalidLengthException::class)->duringInstantiation();
    }

    function it_should_throw_an_exception_when_constructed_with_a_word_that_is_too_short()
    {
        $this->beConstructedThrough('fromString', [str_repeat('a', Word::MINIMUM_LENGTH - 1)]);
        $this->shouldThrow(InvalidLengthException::class)->duringInstantiation();
    }

    function it_should_throw_an_exception_when_constructed_with_a_word_that_is_too_long()
    {
        $this->beConstructedThrough('fromString', [str_repeat('a', Word::MAXIMUM_LENGTH + 1)]);
        $this->shouldThrow(InvalidLengthException::class)->duringInstantiation();
    }

    function it_should_throw_an_exception_when_constructed_with_a_non_alpha_string()
    {
        $this->beConstructedThrough('fromString', ['a12e&c']);
        $this->shouldThrow(InvalidCharactersException::class)->duringInstantiation();
    }

    function it_should_throw_an_exception_when_constructed_with_a_word_that_contains_a_space()
    {
        $this->beConstructedThrough('fromString', ['foo bar']);
        $this->shouldThrow(InvalidCharactersException::class)->duringInstantiation();
    }

    function it_should_throw_an_exception_when_constructed_with_a_blank_string()
    {
        $this->beConstructedThrough('fromString', ['    ']);
        $this->shouldThrow(InvalidCharactersException::class)->duringInstantiation();
    }

    function it_should_return_a_lower_case_version_of_the_word_it_was_constructed_with()
    {
        $this->value()->shouldReturn(strtolower(self::VALID_WORD));
    }

    function it_should_return_true_when_asking_if_it_contains_a_letter_that_is_in_the_word()
    {
        $this->contains('a')->shouldReturn(true);
    }

    function it_should_return_false_when_asking_if_it_contains_a_letter_that_is_not_in_the_word()
    {
        $this->contains('z')->shouldReturn(false);
    }

    function it_should_return_true_when_asking_if_it_can_be_composed_with_its_letters()
    {
        $this->canBeComposedWithLetters(array_unique(str_split(self::VALID_WORD)))->shouldReturn(true);
    }

    function it_should_return_false_when_asking_if_it_can_be_composed_with_not_all_of_its_letters()
    {
        $this->canBeComposedWithLetters(['z', 'a', 'l'])->shouldReturn(false);
    }

    function it_should_return_true_when_asking_it_can_be_composed_by_all_of_its_letters_plus_other_letters()
    {
        $this->canBeComposedWithLetters(array_merge(array_unique(str_split(self::VALID_WORD)), ['z', 'p', 's']))->shouldReturn(true);
    }

    function it_should_be_able_to_represent_itself_as_a_string()
    {
        $this->__toString()->shouldReturn(strtolower(self::VALID_WORD));
    }

}
