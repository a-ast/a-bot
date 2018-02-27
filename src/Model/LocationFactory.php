<?php

namespace App\Model;

use App\Model\Tile\GoldMine;
use App\Model\Tile\Road;
use App\Model\Tile\Tavern;
use App\Model\Tile\Wood;

class LocationFactory
{
    public static function createLocation(string $item, int $x, int $y): Locatable
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