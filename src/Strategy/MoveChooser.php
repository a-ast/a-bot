<?php


namespace App\Strategy;

use App\Model\BoardObject\GoldMine;
use App\Model\BoardObject\Road;
use App\Model\Direction\Directions;
use App\Model\GameState;
use App\Model\Locatable;

class MoveChooser
{
    /**
     * @return array|Locatable[]
     */
    public function getAvailableLocations(GameState $gameState): array
    {
        $hero = $gameState->getHero();
        $board = $gameState->getBoard();

        $locations = [];

        foreach (Directions::getMovableDirections() as $direction) {
            $location = $board->getObjectInDirection($hero, $direction);

            $object = $gameState->getBoard()->getObjectByLocation($location);

            if ($object instanceof Road ||
                ($object instanceof GoldMine && false === $object->belongsMe())) {
                $locations[$direction->getTitle()] = $location;
            }
        }

        return $locations;
    }
}