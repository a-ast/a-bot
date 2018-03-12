<?php

namespace App\Model\Game;

use App\Model\LocationAwareInterface;

interface LocationAwareListInterface extends \Countable
{
    public function add(LocationAwareInterface $item);

    public function get(string $location): LocationAwareInterface;

    public function exists(string $location): bool;

    public function getLocations(): array;

    public function getFilteredList(callable $filter): LocationAwareListInterface;
}
