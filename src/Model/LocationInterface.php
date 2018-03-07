<?php

namespace App\Model;

interface LocationInterface
{
    public function getX(): int;

    public function getY(): int;

    public function isNear(LocationInterface $location): bool;

    public function getCoordinates(): string;
}
