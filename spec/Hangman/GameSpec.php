<?php

namespace spec\Hangman;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hangman\Game;

class GameSpec extends ObjectBehavior
{
    function let()
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Game::class);
    }
}
