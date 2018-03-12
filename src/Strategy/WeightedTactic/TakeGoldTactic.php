<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GameInterface;
use App\Model\HeroInterface;

class TakeGoldTactic extends AbstractWeightedTactic
{

    /**
     * @throws StrategyException
     */
    public function getWeight(GameInterface $game, string $location): int
    {
        if ($game->getBoard()->isGoal($location)) {
            $goal = $game->getBoard()->getGoal($location);

            if ($goal instanceof GoldMine && $goal->getHeroId() === $game->getHero()->getId()) {

                return 0;
            }

            if ($goal instanceof Tavern ||
                $goal instanceof HeroInterface) {
                    $location = $game->getHero()->getLocation();

                //return 0;
            }
        }

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getBoard()->getForeignGoldMines($game->getFriendIds())
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
