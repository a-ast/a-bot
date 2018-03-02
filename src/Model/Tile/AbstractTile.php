<?php

namespace App\Model\Tile;

use App\Model\TileInterface;

abstract class AbstractTile implements TileInterface
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

    public function isNear(TileInterface $tile): bool
    {
        return 1 === $this->getDirectDistanceTo($tile);
    }

    public function isOn(TileInterface $tile): bool
    {
        return 0 === $this->getDirectDistanceTo($tile);
    }

    public function getDirectDistanceTo(TileInterface $tile): int
    {
        return abs($this->getX() - $tile->getX()) +
            abs($this->getY() - $tile->getY());
    }

    public function __toString()
    {
        return $this->getX().':'.$this->getY();
    }
}