<?php

namespace App\Model\Direction;

class Directions
{
    private static $movableDirections;

    /**
     * @return array|\App\Model\Direction\DirectionInterface[]
     */
    public static function getWalkableDirections(): array
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

    public static function getNoDirection(): DirectionInterface
    {
        return new NoDirection();
    }
}