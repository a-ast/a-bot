<?php

namespace App\Game;

use App\Model\Game\Hero;
use App\Model\GameInterface;

class GameDumper
{

    public function dumpHeroes(GameInterface $game)
    {
        $text = 'Rivals:' . PHP_EOL;
        foreach ($game->getRivalHeroes() as $rivalHero) {
            $text .= $this->dumpHero($rivalHero) . PHP_EOL;
        }

        return $text;
    }

    public function dumpHero(Hero $hero)
    {
        return sprintf('[Hero%d @%s] Gold: %d, HP: %sd', 
            $hero->getId(), $hero->getLocation(), 
            $hero->getGoldPoints(), $hero->getLifePoints());
    }
}
