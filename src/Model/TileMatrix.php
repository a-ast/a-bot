<?php

namespace App\Model;

use App\Model\Direction\Pointable;
use App\Model\Tile\Unknown;

class TileMatrix
{
    /**
     * @var array|TileInterface[][]
     */
    private $matrix = [];

    public function getTile(int $x, int $y): TileInterface
    {
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

    public function getTileInDirection(TileInterface $tile, Pointable $direction): TileInterface
    {
        $newX = $tile->getX() + $direction->getShiftX();
        $newY = $tile->getY() + $direction->getShiftY();

        if (!$this->tileExists($newX, $newY)) {
            new Unknown(-1, -1);
        }

        return $this->getTile($newX, $newY);
    }

    public function tileExists(int $x, int $y): bool
    {
        return isset($this->matrix[$x][$y]);
    }
}