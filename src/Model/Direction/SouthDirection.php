<?php

namespace App\Model\Direction;

class SouthDirection implements Pointable
{
    public function getTitle()
    {
        return 'South';
    }

    public function getShiftX()
    {
       return +1;
    }

    public function getShiftY()
    {
        return 0;
    }
}