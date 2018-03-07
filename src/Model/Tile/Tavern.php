<?php

namespace App\Model\Tile;

use App\Model\Location\LocationAwareTrait;
use App\Model\LocationAwareInterface;

class Tavern implements LocationAwareInterface
{
    use LocationAwareTrait;
}
