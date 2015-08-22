<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rhumsaa\Uuid\Uuid;

/** @ORM\Embeddable */
final class GameId
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @param string|null $value
     */
    private function __construct($value)
    {
        /** @noinspection PhpParamsInspection */
        \Assert\that($value)->uuid();

        $this->value = $value;
    }

    /**
     * Get the value of the GameId
     *
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param GameId $id
     *
     * @return bool
     */
    public function equals(GameId $id)
    {
        return $this->value === $id->value();
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    #pragma mark - static

    /**
     * @return GameId
     */
    public static function create()
    {
        return new self(Uuid::uuid4()->toString());
    }
}
