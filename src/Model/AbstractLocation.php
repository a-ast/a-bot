<?php

namespace App\Model;

abstract class AbstractLocation implements TileInterface
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

    public function getDirectDistanceTo(TileInterface $tile): int
    {
        return abs($this->getX() - $tile->getX()) +
            abs($this->getY() - $tile->getY());
    }
}