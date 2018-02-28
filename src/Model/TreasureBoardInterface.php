<?php

namespace App\Model;

use App\Model\Direction\Pointable;
use App\Model\Tile\GoldMine;

interface TreasureBoardInterface extends BoardInterface
{
    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines();
}