<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class TakeNearGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if (false === $game->isGoldMine($location)) {
            return 0;
        }

        if ($game->isGoldMineOfHero($location)) {
            return 0;
        }

        if ($game->getHero()->getLifePoints() <= 21) {
            return -1000;
        }

        $weight = 1000;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isTavern($location)) &&
            (false === $game->isRivalHero($location));
    }
}
