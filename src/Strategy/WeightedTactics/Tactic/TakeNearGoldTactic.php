<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class TakeNearGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): int
    {
        if ($game->getHero()->getLifePoints() <= 21) {
            //return -1000;
            return 0;
        }

        $weight = 1000;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return $game->isGoldMine($location) && (false === $game->isGoldMineOfHero($location));
    }
}
