<?php

namespace Hangman;

interface WordRepositoryInterface
{
    /**
     * @return WordInterface
     */
    function pickARandomWord();
}
