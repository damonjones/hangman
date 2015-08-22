<?php

namespace spec\Hangman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Hangman\InMemoryWordRepository;

class InMemoryWordRepositorySpec extends ObjectBehavior
{
    function let()
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryWordRepository::class);
    }
}
