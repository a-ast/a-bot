<?php

namespace App\Model\Tile;

class Tavern extends AbstractTile
{
    public function isWalkable()
    {
        return false;
    }
}