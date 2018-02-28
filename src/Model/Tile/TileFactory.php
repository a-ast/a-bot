<?php

namespace App\Model\Tile;

use App\Model\TileInterface;

class TileFactory
{
    public static function createTile(string $item, int $x, int $y): TileInterface
    {
        switch ($item) {
            case '##':
                return new Wood($x, $y);
                break;
            case '[]':
                return new Tavern($x, $y);
                break;
        }

        if ('$' === $item[0]) {
            return new GoldMine($x, $y, false);
        }

        return new Road($x, $y);
    }
}