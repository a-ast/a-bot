<?php

namespace App\Model;

use Countable;

interface LocationAwareListInterface extends Countable
{
    public function add(LocationAwareInterface $item, int $index = 0);

    public function get(string $location): LocationAwareInterface;

    public function getByIndex(int $index): LocationAwareInterface;

    public function exists(string $location): bool;

    public function getLocations(): array;

    public function getFilteredList(callable $filter): LocationAwareListInterface;
}
