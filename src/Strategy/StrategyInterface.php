<?php

namespace App\Strategy;

use App\Model\GameInterface;


interface StrategyInterface
{
    public function getAlias(): string;

    public function initialize(GameInterface $game);

    public function getNextLocation(): string;
}
