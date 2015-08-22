<?php

namespace spec\Hangman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Hangman\InMemoryWordRepository;

class InMemoryWordRepositorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['Aardvark', 'Alphabet', 'Zebra']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryWordRepository::class);
    }
}
