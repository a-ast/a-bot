<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class TakeBeerTactic extends AbstractWeightedTactic
{
    /**
     * @throws StrategyException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if ($game->getHero()->getLifePoints() > 95) {
            return 0;
        }

        $totalWeight = 0;

        $source = $location;

//        if ($game->isTavern($source)) {
//            $source = $game->getHero()->getLocation();
//        }

        $goalCount = 0;
        foreach ($game->getTaverns() as $goal) {
            $distanceToGoal = $this->getDistanceToGoal($source, $goal);
            $totalWeight += 1000 * (1/ $distanceToGoal);
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
            (false === $game->isHero($location));
    }
}
