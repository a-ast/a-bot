<?php

namespace App\Strategy\WeightedTactics\Tactic;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Strategy\WeightedTactics\AbstractWeightedTactic;

class AttackWeakHeroTactic extends AbstractWeightedTactic
{
    public function getWeight(GamePlayInterface $game, string $location, bool $isFallbackToHeroLocation): int
    {
        /** @var Hero $rivalHero */
        $rivalHero = $game->getRivalHeroes()->get($location);

        if (
            // if this hero has some gold mines
            (0 === $game->getGoldMinesOf($rivalHero->getId())->count()) ||
            // if he is weaker
            ($rivalHero->getLifePoints() >= $game->getHero()->getLifePoints())) {

            // return -1000;
            return 0;
        }

        return 1000;

    }

    public function isApplicableLocation(GamePlayInterface $game, string $location): bool
    {
        return $game->isRivalHero($location);
    }
}
