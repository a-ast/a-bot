<?php

namespace App\Model;

use App\Model\Direction\Pointable;
use App\Model\Tile\GoldMine;

interface BoardInterface
{
    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines();

    public function getLocationInDirection(Locatable $location, Pointable $direction): Locatable;

    public function getHero(): Movable;
}