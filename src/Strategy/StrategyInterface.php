<?php

namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\TileInterface;

interface StrategyInterface
{
    public function initialize(GameInterface $game);

    public function getNextTile(): TileInterface;
}