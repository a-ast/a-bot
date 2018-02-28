<?php

namespace App\Model\Tile;

class Road extends AbstractTile
{
    public function isWalkable()
    {
        return true;
    }
}