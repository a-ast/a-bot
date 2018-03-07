<?php

namespace App\PathFinder;

use App\Model\LocationInterface;
use App\Model\Location\LocationMatrixInterface;

interface PathFinderInterface
{
    public function initialize(LocationMatrixInterface $locations, array $context = []);

    public function getDistance(LocationInterface $fromLocation, LocationInterface $toLocation): int;

    public function getNextLocation(LocationInterface $fromLocation, LocationInterface $toLocation): LocationInterface;
}
