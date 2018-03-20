<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class FindWeakHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): float
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

            $distanceToGoal = $this->getDistanceToGoal($location, $goal, $isFallbackToHeroLocation);

            /**
             *
             * Initial formula that allowed to win:
             * $k = 0.5;
             * $totalWeight += 1000 * $k * (1 / ($distanceToGoal + 1));
             *
             */
            $totalWeight += $this->getBalancedWeightFromDistance($distanceToGoal);

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
        return $game->isWalkableAt($location);
    }
}
