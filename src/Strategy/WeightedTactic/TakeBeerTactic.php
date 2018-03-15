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
        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getTaverns()
        );

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    public function isApplicable(GamePlayInterface $game, string $location): bool
    {
        return
            ($game->getHero()->getLifePoints() < 95) &&
            (false === $game->isGoldMine($location)) &&
            (false === $game->isHero($location));
    }
}
