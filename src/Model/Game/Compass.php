<?php

namespace App\Model\Game;

use App\Model\Location\Location;

class Compass
{

    /**
     * @throws \Exception
     */
    public function getDirectionTo(string $from, string $to): string
    {
        list($fromX, $fromY) = Location::getXY($from);
        list($toX, $toY) = Location::getXY($to);
        
        $xDiff = $fromX - $toX;
        $yDiff = $fromY - $toY;

        switch ($xDiff . ':' . $yDiff) {
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
