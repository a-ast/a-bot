<?php

namespace App\Model;

use App\Model\Direction\DirectionInterface;
use App\Model\Tile\GoldMine;

interface BoardInterface
{
    public function getTileAt(int $x, int $y): TileInterface;

    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface;

    public function getWalkableNearTiles(TileInterface $tile, TileInterface $includedGoal): array;
}