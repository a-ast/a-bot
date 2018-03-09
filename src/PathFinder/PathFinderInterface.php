<?php

namespace App\PathFinder;

use App\Model\Game\LocationAwareMapInterface;
use App\Model\LocationInterface;
use App\Model\Location\LocationMapInterface;

interface PathFinderInterface
{
    public function initialize(LocationMapInterface $locations,
        LocationAwareMapInterface $goals = null, array $context = []);

    public function getDistance(LocationInterface $fromLocation, LocationInterface $toLocation): int;

    public function getNextLocation(LocationInterface $fromLocation, LocationInterface $toLocation): LocationInterface;
}
