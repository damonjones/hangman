<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hangman\Word as BaseWord;
use Hangman\WordInterface;

/** @ORM\Embeddable */
final class Word extends BaseWord
{
    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    protected $value;

    /**
     * @param WordInterface $value
     *
     * @return Word
     */
    public static function fromWord(WordInterface $word)
    {
        return static::fromString($word->value());
    }
}
