<?php


namespace App\Model;


use App\Model\Direction\Pointable;

class Location extends AbstractLocation
{
    public static function moveTo(Locatable $location, Pointable $direction)
    {
        return new self(
            $location->getX() + $direction->getShiftX(),
            $location->getY() + $direction->getShiftY()
        );
    }
}