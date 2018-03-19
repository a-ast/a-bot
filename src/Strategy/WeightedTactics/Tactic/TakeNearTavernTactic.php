<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class TakeNearTavernTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if ($game->getHero()->getLifePoints() > 80) {
            return 0;
        }

        $weight = $game->isTavern($location) ? 1000 : 0;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isGoldMine($location)) &&
            (false === $game->isRivalHero($location));
    }
}
