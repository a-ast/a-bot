<?php

namespace App\Model\Location;

use App\Model\LocationInterface;

interface LocationMatrixInterface
{
    public function addLocation(LocationInterface $location);

    // public function getLocation(int $x, int $y): LocationInterface;

    public function isNear($iLoc, $jLoc);

    public function getNearLocations(LocationInterface $location): array;

    public function getCoordinates(): array;
}
