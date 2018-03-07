<?php

namespace App\Model\Tile;

use App\Model\Location\LocationAwareTrait;
use App\Model\LocationInterface;
use App\Model\LocationAwareInterface;

class GoldMine implements LocationAwareInterface
{
    use LocationAwareTrait;
}
