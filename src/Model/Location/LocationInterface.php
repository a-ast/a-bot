<?php

namespace App\Model\Location;

interface LocationInterface
{
    public function getX(): int;

    public function getY(): int;

    public function isNear(LocationInterface $location): bool;
}
