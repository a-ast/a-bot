<?php

namespace App\Model\Game;

use App\Model\LocationInterface;

class Compass
{

    /**
     * @throws \Exception
     */
    public function getDirectionTo(LocationInterface $from, LocationInterface $to): string
    {
        $xDiff = $from->getX() - $to->getX();
        $yDiff = $from->getY() - $to->getY();

        switch ($xDiff.':'.$yDiff) {
            case '1:0':
                return 'North';
            case '-1:0':
                return 'South';
            case '0:1':
                return 'West';
            case '0:-1':
                return 'East';
            case '0:0':
                return 'Stay';
        }

        throw new \Exception('Teleport is not invented yet.');
    }
}
