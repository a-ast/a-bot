<?php

namespace App\Model;

use App\Model\Direction\DirectionInterface;

interface BoardInterface
{
    public function getTileAt(int $x, int $y): TileInterface;

    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface;

    /**
     * @return array|\App\Model\TileInterface[]
     */
    public function getNearTiles(TileInterface $tile, bool $onlyWalkable = true): array;
}