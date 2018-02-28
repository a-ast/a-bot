<?php

namespace App\Model\Tile;

class Wood extends AbstractTile
{
    public function isWalkable()
    {
        return false;
    }
}