<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class AttackWeakHeroTactic extends AbstractWeightedTactic
{
    /**
     * @throws StrategyException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {


        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $game->getRivalHeroes()
        );

        $distance = $locationWithDistance->getPriority();
        $closestLocation = $locationWithDistance->getLocation();

        /** @var Hero $rival */
        $rival = $game->getRivalHeroes()->get($closestLocation);

        if ($distance < 3 &&
            $rival->getLifePoints() < $game->getHero()->getLifePoints() &&
            count($game->getGoldMinesOf($rival->getId())) > 0

            // do not attack heroes that stay on their spawn ?
            && !($rival->isOnSpawnLocation() && $distance === 1)

        ) {
            print sprintf('### Attack rival at %s|%s. Distance: %d###', $closestLocation, $rival->getLocation(), $distance);

            return 1000 - 10 * $distance;
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
