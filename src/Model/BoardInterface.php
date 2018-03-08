<?php

namespace App\Model;

use App\Model\Game\LocationAwareMapInterface;
use App\Model\Location\LocationMatrixInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

interface BoardInterface
{
    public function getWidth(): int;

    /**
     * @return LocationMatrixInterface
     */
    public function getMap(): LocationMatrixInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMines(): LocationAwareMapInterface;

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(array $friendHeroIds): LocationAwareMapInterface;

    /**
     * @return Tavern[]
     */
    public function getTaverns(): LocationAwareMapInterface;
}
