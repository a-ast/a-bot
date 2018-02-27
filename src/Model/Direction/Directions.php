<?php

namespace App\Model\Direction;

class Directions
{
    private static $movableDirections;

    /**
     * @return array|\App\Model\Direction\Pointable[]
     */
    public static function getMovableDirections(): array
    {
        if (null === static::$movableDirections) {
            static::$movableDirections = [
                new NorthDirection(),
                new WestDirection(),
                new SouthDirection(),
                new EastDirection(),
            ];
        }

        return static::$movableDirections;
    }

    public static function getNoDirection(): Pointable
    {
        return new NoDirection();
    }
}