<?php

namespace App\Model;

use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

interface TreasureBoardInterface extends BoardInterface
{
    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines();

    /**
     * @return Tavern[]|array
     */
    public function getTaverns();
}