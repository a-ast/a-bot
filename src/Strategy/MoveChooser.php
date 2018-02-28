<?php


namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Road;
use App\Model\Direction\Directions;
use App\Model\TileInterface;

class MoveChooser
{
    /**
     * @return array|TileInterface[]
     */
    public function getAvailableLocations(GameInterface $game): array
    {
        $hero = $game->getHero();
        $board = $game->getBoard();

        $tiles = [];

        foreach (Directions::getMovableDirections() as $direction) {
            $object = $board->getTileInDirection($hero, $direction);

            if ($object instanceof Road ||
                ($object instanceof GoldMine && !$object->belongsTo($hero))) {
                $tiles[$direction->getTitle()] = $object;
            }
        }

        return $tiles;
    }
}