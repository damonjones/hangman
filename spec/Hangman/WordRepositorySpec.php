<?php

namespace spec\Hangman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hangman\WordRepository;

class WordRepositorySpec extends ObjectBehavior
{
    function let()
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(WordRepository::class);
    }
}
