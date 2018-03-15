<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class AvoidStrongHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        $totalWeight = 0;

        $source = $location;

        //        if ($game->isGoldMine($source)) {
        //            $source = $game->getHero()->getLocation();
        //        }

        $goalCount = 0;
        foreach ($game->getRivalHeroes() as $goal) {

            if ($goal->getLifePoints() < $game->getHero()->getLifePoints()) {
                continue;
            }

            $distanceToGoal = $this->getDistanceToGoal($source, $goal);

            $totalWeight += 1000 - 1000 * (1/ $distanceToGoal);
            $goalCount++;
        }

        if (0 === $goalCount) {
            return 0;
        }

        $weight = $totalWeight/$goalCount;

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isGoldMine($location)) &&
            (false === $game->isTavern($location));
    }
}
