<?php

namespace App\Model;

use App\Model\Location\LocationMatrixInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

interface BoardInterface
{
    /**
     * @return LocationMatrixInterface
     */
    public function getRoads(): LocationMatrixInterface;

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines(): array;

    /**
     * @return GoldMine[]|array
     */
    public function getForeignGoldMines();

    /**
     * @return Tavern[]|array
     */
    public function getTaverns(): array;
}
