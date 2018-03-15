<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class AvoidStrongHeroTactic extends AbstractWeightedTactic
{
    /**
     * @throws StrategyException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if (0 === $game->getRivalHeroes()->count()) {
            return 0;
        }

        if ($game->isGoldMine($location) || $game->isTavern($location)) {
            return 0;
        }

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getRivalHeroes()
        );

        $distance = $locationWithDistance->getPriority();
        $closestLocation = $locationWithDistance->getLocation();

        /** @var Hero $rival */
        $rival = $game->getRivalHeroes()->get($closestLocation);

        if ($distance < 3 &&
            $rival->getLifePoints() > $game->getHero()->getLifePoints()
        ) {
            // @todo: scale to 1000
            return 10 * $distance;
        }

        return 0;
    }

    public function isApplicable(GamePlayInterface $game, string $location): bool
    {
        return
            ($game->getRivalHeroes()->count() > 0) &&
            (false === $game->isGoldMine($location)) &&
            (false === $game->isTavern($location));
    }
}
