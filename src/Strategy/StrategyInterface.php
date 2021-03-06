<?php

namespace App\Strategy;

use App\Model\GamePlayInterface;

interface StrategyInterface
{
    public function initialize(GamePlayInterface $game);

    public function getNextLocation(): string;

    public function getTacticStatistics(): TacticStatistics;
}
