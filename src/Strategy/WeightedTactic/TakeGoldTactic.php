<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class TakeGoldTactic extends AbstractWeightedTactic
{

    /**
     * @throws StrategyException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {

        $totalWeight = 0;

        $source = $location;


        // process state when you stay near goldmine

        if ($game->isGoldMine($source)) {

            $goal = $game->getGameObjectAt($source);

            if ($goal->getHeroId() === $game->getHero()->getId()) {
                $source = $game->getHero()->getLocation();
            }
        }

        $goalCount = 0;
        foreach ($game->getForeignGoldMines() as $goal) {

            $distanceToGoal = $this->getDistanceToGoal($source, $goal);

            if ($game->getHero()->getLifePoints() - $distanceToGoal <= 21) {
                continue;
            }

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
            //($game->getHero()->getLifePoints() >= 21) &&
            (false === $game->isHero($location)) &&
            (false === $game->isTavern($location))
        ;
    }
}
