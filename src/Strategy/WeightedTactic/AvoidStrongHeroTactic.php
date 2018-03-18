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
        $goalCount = 0;

        foreach ($game->getRivalHeroes() as $goal) {

            if ($goal->getLifePoints() < $game->getHero()->getLifePoints()) {
                continue;
            }

            $distanceToGoal = $this->getDistanceToGoal($location, $goal);

            if ($distanceToGoal > 4) {
                continue;
            }

            $totalWeight += 1000 - 1000 * (1 / ($distanceToGoal + 1));

            $goalCount++;
        }

        if (0 === $goalCount) {
            return 0;
        }

        $weight = $totalWeight/$goalCount;

        // @todo: if weight > 0, track it
        // if zero, flush counter
        // if 3-4 time avoiding, check if it makes sense and disable avoiding

        return $weight;
    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isGoldMine($location)) &&
            (false === $game->isTavern($location));
    }
}
