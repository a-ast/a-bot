<?php

namespace App\Model\Direction;

class EastDirection implements Pointable
{
    public function getTitle()
    {
        return 'East';
    }

    public function getShiftX()
    {
       return 0;
    }

    public function getShiftY()
    {
        return +1;
    }
}