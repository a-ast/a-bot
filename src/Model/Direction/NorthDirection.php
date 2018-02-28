<?php

namespace App\Model\Direction;

class NorthDirection implements DirectionInterface
{
    public function getTitle()
    {
        return 'North';
    }

    public function getShiftX()
    {
       return -1;
    }

    public function getShiftY()
    {
        return 0;
    }
}