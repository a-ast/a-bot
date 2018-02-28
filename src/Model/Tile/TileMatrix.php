<?php

namespace App\Model\Tile;

use App\Model\Direction\DirectionInterface;
use App\Model\TileInterface;

class TileMatrix
{
    /**
     * @var array|TileInterface[][]
     */
    private $matrix = [];

    public function getTile(int $x, int $y): TileInterface
    {
        if (!$this->tileExists($x, $y)) {
            return new Unknown(-1, -1);
        }

        return $this->matrix[$x][$y];
    }

    public function addTile(TileInterface $tile)
    {
        $this->matrix[$tile->getX()][$tile->getY()] = $tile;
    }

    public function reset()
    {
        $this->matrix = [];
    }

    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface
    {
        $newX = $tile->getX() + $direction->getShiftX();
        $newY = $tile->getY() + $direction->getShiftY();

        return $this->getTile($newX, $newY);
    }

    public function tileExists(int $x, int $y): bool
    {
        return isset($this->matrix[$x][$y]);
    }
}