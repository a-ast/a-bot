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
        if ($game->isGoldMine($location)) {
            /** @var GoldMine $goldMine */
            $goldMine = $game->getGameObjectAt($location);

            if ($goldMine->getHeroId() === $game->getHero()->getId()) {
                return 0;
            }
        }

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getForeignGoldMines()
        );

        $distance = $locationWithDistance->getPriority();
        if ($game->getHero()->getLifePoints() - $distance <= 21) {
            return 0;
        }

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    public function isApplicable(GamePlayInterface $game, string $location): bool
    {
        return
            ($game->getHero()->getLifePoints() >= 21) &&
            (false === $game->isHero($location)) &&
            (false === $game->isTavern($location)) &&
            ($game->getForeignGoldMines()->count() > 0)
        ;
    }
}
