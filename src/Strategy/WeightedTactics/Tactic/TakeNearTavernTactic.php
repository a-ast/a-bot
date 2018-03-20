<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class TakeNearTavernTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): float
    {
        if ($game->getHero()->getLifePoints() > 80) {
            return 0;
        }

        $weight = 1000;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return $game->isTavern($location);
    }
}
