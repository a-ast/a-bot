<?php

namespace App\PathFinder;

use App\Model\LocationGraphInterface;

interface PathFinderInterface
{
    public function initialize(LocationGraphInterface $locationGraph,
        array $goalLocations = [], array $context = []);

    public function getDistance(string $from, string $to): int;

    public function getNextLocation(string $from, string $to): string;
}
