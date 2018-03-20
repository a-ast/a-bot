<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class AvoidStrongHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): float
    {
        $totalWeight = 0;
        $goalCount = 0;

        foreach ($game->getRivalHeroes() as $goal) {

            if ($goal->getLifePoints() < $game->getHero()->getLifePoints()) {
                continue;
            }

            $distanceToGoal = $this->getDistanceToGoal($location, $goal, $isFallbackToHeroLocation);

            if ($distanceToGoal > 4) {
                continue;
            }

            $totalWeight += 1000 - $this->getBalancedWeightFromDistance($distanceToGoal);

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
        return $game->isWalkableAt($location);
    }
}
