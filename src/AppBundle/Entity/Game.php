<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hangman\Game as BaseGame;
use Hangman\WordInterface;

/**
 * @ORM\Entity
 */
final class Game extends BaseGame
{
    /**
     * @var GameId
     *
     * @ORM\Id
     * @ORM\Embedded(class="GameId")
     */
    private $id;

    /**
     * @var WordInterface
     *
     * @ORM\Embedded(class="Word")
     */
    protected $word;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $guessedLetters = [];

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $guessesRemaining = self::NUMBER_OF_GUESSES;

    /**
     * @param WordInterface $word
     */
    protected function __construct(WordInterface $word)
    {
        $this->word = $word;
        $this->id   = GameId::create();
    }

    /**
     * Get the id of the Game
     *
     * @return GameId
     */
    public function getId()
    {
        return $this->id;
    }
}
