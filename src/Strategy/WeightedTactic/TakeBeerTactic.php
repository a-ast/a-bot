<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Game\GoldMine;
use App\Model\GameInterface;
use App\Model\HeroInterface;

class TakeBeerTactic extends AbstractWeightedTactic
{

    /**
     * @throws StrategyException
     */
    public function getWeight(GameInterface $game, string $location): int
    {
        if ($game->getHero()->getLifePoints() > 90) {
            return 0;
        }

        if ($game->getBoard()->isGoal($location)) {
            $goal = $game->getBoard()->getGoal($location);

            if ($goal instanceof GoldMine ||
                $goal instanceof HeroInterface
            ) {
                $location = $game->getHero()->getLocation();
            }
        }


        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getBoard()->getTaverns()
        );

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    public function getAlias(): string
    {
        return 'take bear';
    }
}
