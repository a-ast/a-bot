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
}
