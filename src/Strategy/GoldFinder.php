<?php

namespace App\Strategy;

use App\Model\BoardObject\GoldMine;
use App\Model\GameState;

class GoldFinder
{
    public function getClosestGoldMine(GameState $gameState): GoldMine
    {
        $minDistance = 10000;

        // @todo: incorrect choise
        $closestGoldMine = $gameState->getBoard()->getGoldMines()[0];

        $hero = $gameState->getHero();

        foreach ($gameState->getBoard()->getGoldMines() as $goldMine) {

            if ($goldMine->belongsMe()) {
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