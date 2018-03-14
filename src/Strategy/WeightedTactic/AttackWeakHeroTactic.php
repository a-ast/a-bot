<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class AttackWeakHeroTactic extends AbstractWeightedTactic
{
    /**
     * @throws StrategyException
     * @throws GamePlayException
     */
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if (0 === $game->getRivalHeroes()->count()) {
            return 0;
        }

        if ($game->isGameObjectAt($location)) {
            $goal = $game->getGameObjectAt($location);

            if ($goal instanceof GoldMine ||
                $goal instanceof Tavern
            ) {
                $location = $game->getHero()->getLocation();
            }
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
}
