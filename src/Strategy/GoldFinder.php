<?php

namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\Tile\GoldMine;

class GoldFinder
{
    public function getClosestGoldMine(GameInterface $gameState): GoldMine
    {
        $minDistance = 10000;

        // @todo: incorrect choise
        $closestGoldMine = $gameState->getBoard()->getGoldMines()[0];

        $hero = $gameState->getHero();

        foreach ($gameState->getBoard()->getGoldMines() as $goldMine) {

            if ($goldMine->belongsTo($gameState->getHero())) {
                continue;
            }

            $distance = $hero->getDirectDistanceTo($goldMine);

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestGoldMine = $goldMine;
            }
        }

        return $closestGoldMine;
    }
}