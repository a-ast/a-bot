<?php

namespace App\Model;

abstract class AbstractLocation implements Locatable
{
    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getDirectDistanceTo(Locatable $location): int
    {
        return abs($this->getX() - $location->getX()) +
            abs($this->getY() - $location->getY());
    }
}