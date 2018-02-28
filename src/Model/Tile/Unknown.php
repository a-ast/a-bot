<?php

namespace App\Model\Tile;

class Unknown extends AbstractTile
{
    public function isWalkable()
    {
        return false;
    }
}