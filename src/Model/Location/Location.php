<?php

namespace App\Model\Location;

final class Location
{
    /**
     * @return string[]
     */
    public static function getXY($location): array
    {
        return explode(':', $location);
    }

    public static function getLocation($x, $y): string
    {
        return $x . ':' . $y;
    }

    public static function isNear(string $location1, string $location2): bool
    {
        list($x1, $y1) = self::getXY($location1);
        list($x2, $y2) = self::getXY($location2);

        return 1 === (abs($x1 - $x2) + abs($y1 - $y2));
    }
}
