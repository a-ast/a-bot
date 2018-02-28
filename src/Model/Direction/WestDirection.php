<?php

namespace App\Model\Direction;

class WestDirection implements DirectionInterface
{
    public function getTitle()
    {
        return 'West';
    }

    public function getShiftX()
    {
       return 0;
    }

    public function getShiftY()
    {
        return -1;
    }
}