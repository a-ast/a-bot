<?php

namespace App\Model\Tile;

class NoTile extends AbstractTile
{
    public function isWalkable()
    {
        return false;
    }
}