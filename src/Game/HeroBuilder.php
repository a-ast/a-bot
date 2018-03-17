<?php

namespace App\Game;

use App\Model\Game\Hero;
use App\Model\Location\Location;

class HeroBuilder
{
    public function buildHero(array $data): Hero
    {
        $id = $data['id'];
        $name = $data['name'];

        $location = Location::getLocation($data['pos']['x'], $data['pos']['y']);
        $spawnLocation = Location::getLocation($data['spawnPos']['x'], $data['spawnPos']['y']);
        
        $hero = new Hero($id, $name, $location, $spawnLocation);
        
        $this->updateHero($hero, $data);
        
        return $hero;
    }

    public function updateHero(Hero $hero, array $data)
    {
        if (isset($data['life'])) {
            $hero->setLifePoints($data['life']);
        }

        if (isset($data['gold'])) {
            $hero->setGoldPoints($data['gold']);
        }

        if (isset($data['crashed'])) {
            $hero->setCrashed($data['crashed']);
        }

        $newLocation = Location::getLocation($data['pos']['x'], $data['pos']['y']);

        $hero->setRespawned(
            !Location::isNear($hero->getLocation(), $newLocation)  &&
            ($newLocation === $hero->getSpawnLocation())
        );

        $hero->setLocation($newLocation);
    }
}
