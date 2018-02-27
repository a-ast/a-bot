<?php

namespace App\Model\Direction;

class NoDirection implements Pointable
{
    public function getTitle()
    {
        return 'Stay';
    }

    public function getShiftX()
    {
       return 0;
    }

    public function getShiftY()
    {
        return 0;
    }
}