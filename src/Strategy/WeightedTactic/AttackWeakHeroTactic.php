<?php

namespace App\Strategy\WeightedTactic;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;

class AttackWeakHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location): int
    {
        if (false === $game->isRivalHero($location)) {
            return 0;
        }

        /** @var Hero $rivalHero */
        $rivalHero = $game->getRivalHeroes()->get($location);


        if (
            // if this hero has some gold mines
            (0 === $game->getGoldMinesOf($rivalHero->getId())->count()) ||
            // if he is weaker
            ($rivalHero->getLifePoints() >= $game->getHero()->getLifePoints())) {

            return -1000;
        }

        return 1000;

    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return
            (false === $game->isGoldMine($location)) &&
            (false === $game->isTavern($location));
    }
}
