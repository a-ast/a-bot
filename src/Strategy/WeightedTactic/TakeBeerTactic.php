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
     * @throws GamePlayException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if ($game->getHero()->getLifePoints() > 90) {
            return 0;
        }

        if ($game->isGameObjectAt($location)) {

            $goal = $game->getGameObjectAt($location);

            if ($goal instanceof GoldMine ||
                $goal instanceof Hero
            ) {
                $location = $game->getHero()->getLocation();
            }
        }

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getTaverns()
        );

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    public function getAlias(): string
    {
        return 'take bear';
    }
}
