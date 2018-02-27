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
            $object = $board->getLocationInDirection($hero, $direction);

            if ($object instanceof Road ||
                ($object instanceof GoldMine && false === $object->belongsMe())) {
                $locations[$direction->getTitle()] = $object;
            }
        }

        return $locations;
    }
}