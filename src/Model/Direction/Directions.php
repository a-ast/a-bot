<?php

namespace App\Model\Direction;

class Directions
{
    private static $walkableDirections;

    /**
     * @return array|\App\Model\Direction\DirectionInterface[]
     */
    public static function getWalkableDirections(): array
    {
        if (null === static::$walkableDirections) {
            static::$walkableDirections = [
                new NorthDirection(),
                new WestDirection(),
                new SouthDirection(),
                new EastDirection(),
            ];
        }

        return static::$walkableDirections;
    }

    public static function getNoDirection(): DirectionInterface
    {
        return new NoDirection();
    }
}