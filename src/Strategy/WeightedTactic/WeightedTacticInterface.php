<?php

namespace App\Strategy\WeightedTactic;

use App\Model\GameInterface;

interface WeightedTacticInterface
{
    public function getWeight(GameInterface $game, string $location): int;

    public function getAlias(): string;
}
