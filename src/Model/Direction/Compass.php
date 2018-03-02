<?php

namespace App\Model\Direction;

use App\Model\TileInterface;

class Compass
{

    /**
     * @throws \Exception
     */
    public function getDirectionTo(TileInterface $fromTile, TileInterface $toTile): string
    {
        $xDiff = $fromTile->getX() - $toTile->getX();
        $yDiff = $fromTile->getY() - $toTile->getY();

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