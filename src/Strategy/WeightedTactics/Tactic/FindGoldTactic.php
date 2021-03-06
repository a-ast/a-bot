<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class FindGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): float
    {
        $totalWeight = 0;
        $goalCount = 0;

        foreach ($game->getForeignGoldMines() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($location, $goal, $isFallbackToHeroLocation);

            if ($game->getHero()->getLifePoints() - $distanceToGoal <= 21) {
                continue;
            }

            $totalWeight += $this->getBalancedWeightFromDistance($distanceToGoal);

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
        return $game->isWalkableAt($location);
    }
}
