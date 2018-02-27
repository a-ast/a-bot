<?php

namespace App\Strategy;

use App\Model\GameStateInterface;
use App\Model\Tile\GoldMine;

class GoldFinder
{
    public function getClosestGoldMine(GameStateInterface $gameState): GoldMine
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