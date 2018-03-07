<?php

namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\LocationInterface;

interface StrategyInterface
{
    public function initialize(GameInterface $game);

    public function getNextLocation(): LocationInterface;
}
