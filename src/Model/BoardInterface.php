<?php

namespace App\Model;

use App\Model\Direction\Pointable;
use App\Model\Tile\GoldMine;

interface BoardInterface
{
    public function getTileInDirection(TileInterface $tile, Pointable $direction): TileInterface;
}