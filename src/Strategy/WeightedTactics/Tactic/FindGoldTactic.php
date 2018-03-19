<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class FindGoldTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): int
    {
        $totalWeight = 0;
        $goalCount = 0;


        foreach ($game->getForeignGoldMines() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($location, $goal);

            // this is that gold
            if (1 === $distanceToGoal && $isFallbackToHeroLocation) {
                // @todo: really exclude?
                continue;
            }

            // if it is another object then distance will be one more step
            if ($isFallbackToHeroLocation) {
                $distanceToGoal++;
            }

            if ($game->getHero()->getLifePoints() - $distanceToGoal <= 21) {
                continue;
            }

            $k = 0.5;
            $totalWeight += 1000 * (1 / ($k * ($distanceToGoal + 1)));

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
