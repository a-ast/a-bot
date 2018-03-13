<?php

namespace App\Strategy\WeightedTactic;

use App\Model\GamePlayInterface;

interface WeightedTacticInterface
{
    public function getWeight(GamePlayInterface $game, string $location): int;

    public function getAlias(): string;
}
