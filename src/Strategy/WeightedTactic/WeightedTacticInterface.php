<?php

namespace App\Strategy\WeightedTactic;

use App\Model\GamePlayInterface;

interface WeightedTacticInterface
{
    public function getWeight(GamePlayInterface $game, string $location): int;

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool;

    //public function isWalkable(GamePlayInterface $game, string $location): bool;
}
