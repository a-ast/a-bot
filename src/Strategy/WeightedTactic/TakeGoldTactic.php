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
     * @throws GamePlayException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        // If you have all gold, do something else. E.g., find a girl.
        if (0 === $game->getForeignGoldMines()->count()) {
            return 0;
        }

        if ($game->isGameObjectAt($location)) {
            $goal = $game->getGameObjectAt($location);

            if ($goal instanceof GoldMine && $goal->getHeroId() === $game->getHero()->getId()) {
                return 0;
            }

            if ($goal instanceof Tavern || $goal instanceof Hero) {
                $location = $game->getHero()->getLocation();
            }
        }

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getForeignGoldMines()
        );

        $distance = $locationWithDistance->getPriority();
        if ($game->getHero()->getLifePoints() - $distance < 25) {
            return 0;
        }

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    public function getAlias(): string
    {
        return 'take gold';
    }
}
