<?php

namespace App\Model;

use App\Model\Game\LocationAwareListInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

interface BoardInterface
{
    public function getWidth(): int;

    /**
     * @return LocationGraphInterface
     */
    public function getMap(): LocationGraphInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMines(): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(array $friendHeroIds): LocationAwareListInterface;

    /**
     * @return Tavern[]
     */
    public function getTaverns(): LocationAwareListInterface;

    /**
     * @return string[]
     */
    public function getGoalLocations(): array;

    /**
     * @return string[]
     */
    public function getWalkableLocations(): array;
}
