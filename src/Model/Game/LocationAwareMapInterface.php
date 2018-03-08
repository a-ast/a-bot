<?php

namespace App\Model\Game;

use App\Model\LocationAwareInterface;

interface LocationAwareMapInterface
{

    public function add(LocationAwareInterface $item);

    public function getCoordinateList(): array;

    public function getByCoordinates(string $coordinates): LocationAwareInterface;
}
