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
        $source = $location;

        $goalCount = 0;
        foreach ($game->getTaverns() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($source, $goal);

            // this is that tavern
            if (1 === $distanceToGoal && $isFallbackToHeroLocation) {
                // @todo: really exclude?
                continue;
            }

            // if it is another object then distance will be one more step
            if ($isFallbackToHeroLocation) {
                $distanceToGoal++;
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
