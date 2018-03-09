<?php

namespace App\Model\Location;

use App\Model\LocationInterface;

interface LocationMapInterface
{
    public function add(LocationInterface $location);

    /**
     * @return LocationInterface[]
     */
    public function getNearLocations(LocationInterface $location): array;

    public function getCoordinatesList(): array;
}
