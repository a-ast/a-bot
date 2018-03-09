<?php

namespace App\Model\Game;

use App\Model\LocationAwareInterface;

interface LocationAwareMapInterface
{
    public function add(LocationAwareInterface $item);

    public function getCoordinatesList(): array;

    public function getByCoordinates(string $coordinates): LocationAwareInterface;

    public function addMap(LocationAwareMapInterface $map): LocationAwareMapInterface;
}
