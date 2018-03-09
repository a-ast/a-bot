<?php

namespace App\Model;

use App\Model\Game\LocationAwareMapInterface;
use App\Model\Location\LocationMapInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

interface BoardInterface
{
    public function getWidth(): int;

    /**
     * @return LocationMapInterface
     */
    public function getMap(): LocationMapInterface;

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
