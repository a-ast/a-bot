<?php

namespace App\Model\Game;

use App\Model\Location\LocationAwareTrait;
use App\Model\LocationAwareInterface;

class Tavern implements LocationAwareInterface
{
    use LocationAwareTrait;
}
