<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class FindGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        $totalWeight = 0;
        $goalCount = 0;

        foreach ($game->getForeignGoldMines() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($location, $goal);

            if ($game->getHero()->getLifePoints() - $distanceToGoal <= 21) {
                continue;
            }

            // plus one to avoid dividing by zero
            $totalWeight += 1000 * (1 / ($distanceToGoal + 1));

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
        return (false === $game->isGameObjectAt($location));
    }
}
