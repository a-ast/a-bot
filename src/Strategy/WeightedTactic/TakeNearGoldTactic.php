<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class TakeNearGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if ($game->isGoldMine($location) &&
            $game->isGoldMineOfHero($location) &&
            $game->getHero()->getLifePoints() <= 21) {

            return -1000;
        }

        if (false === $game->isGoldMine($location)) {
            return 0;
        }

        $weight = $game->isGoldMineOfHero($location) ? 0 : 1000;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isTavern($location)) &&
            (false === $game->isRivalHero($location));
    }
}
