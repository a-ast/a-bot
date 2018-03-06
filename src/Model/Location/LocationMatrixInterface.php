<?php

namespace App\Model\Location;

interface LocationMatrixInterface
{

    public function addLocation(LocationInterface $location);

    public function getLocation(int $x, int $y): LocationInterface;
}
