<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class FindTavernTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): int
    {
        if ($game->getHero()->getLifePoints() > 80) {
            return 0;
        }

        $totalWeight = 0;
        $goalCount = 0;

        foreach ($game->getTaverns() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($location, $goal, $isFallbackToHeroLocation);
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
