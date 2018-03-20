<?php

namespace App\Strategy\WeightedTactics;

use App\Model\GamePlayInterface;

interface WeightedTacticInterface
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): float;

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool;
}
