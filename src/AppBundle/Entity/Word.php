<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hangman\Word as BaseWord;

/** @ORM\Embeddable */
final class Word extends BaseWord
{
    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     */
    protected $value;
}
