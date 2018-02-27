<?php


namespace App\Model\Direction;


class WestDirection implements Pointable
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