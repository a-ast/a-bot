<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class FindWeakHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        $totalWeight = 0;
        $goalCount = 0;

        foreach ($game->getRivalHeroes() as $goal) {

            if (0 === $game->getGoldMinesOf($goal->getId())->count()) {
                continue;
            }

            if ($goal->getLifePoints() >= $game->getHero()->getLifePoints()) {
                continue;
            }

            $distanceToGoal = $this->getDistanceToGoal($location, $goal);

            $totalWeight += 1000 * (1 / ($distanceToGoal + 1));
            $goalCount++;
        }

        if (0 === $goalCount) {
            return 0;
        }

        $weight = $totalWeight / $goalCount;

        return $weight;

    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return (false === $game->isGameObjectAt($location));
    }
}
