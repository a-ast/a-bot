<?php

namespace App\Model;

use App\Model\Direction\DirectionInterface;
use App\Model\Tile\GoldMine;

interface BoardInterface
{
    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface;
}