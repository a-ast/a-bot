<?php

namespace App\Model\Location;

use App\Model\LocationAwareInterface;
use App\Model\LocationAwareListInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationAwareList implements IteratorAggregate, LocationAwareListInterface
{
    /**
     * @var array
     */
    private $items = [];

    private $indexedItems = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add(LocationAwareInterface $item, int $index = 0)
    {
        $this->items[$item->getLocation()] = $item;

        if (0 !== $index) {
            $this->indexedItems[$index] = $item;
        }
    }

    public function get(string $location): LocationAwareInterface
    {
        return $this->items[$location];
    }

    public function getLocations(): array
    {
        return array_keys($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function getFilteredList(callable $filter): LocationAwareListInterface
    {
        $filteredList = array_filter($this->items, $filter);

        // @todo: filter indexes

        return new LocationAwareList($filteredList);
    }

    public function count()
    {
        return count($this->items);
    }

    public function exists(string $location): bool
    {
        return isset($this->items[$location]);
    }

    public function getByIndex(int $index): LocationAwareInterface
    {
        return $this->indexedItems[$index];
    }
}
