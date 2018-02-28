<?php

namespace App\Model;

use App\Model\Direction\DirectionInterface;
use App\Model\Tile\GoldMine;

interface TreasureBoardInterface extends BoardInterface
{
    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines();
}