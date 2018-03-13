<?php

namespace App\Strategy;

use App\Model\GamePlayInterface;


interface StrategyInterface
{
    public function getAlias(): string;

    public function initialize(GamePlayInterface $game);

    public function getNextLocation(): string;
}
