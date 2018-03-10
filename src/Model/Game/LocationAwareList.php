<?php

namespace App\Model\Game;

use App\Model\LocationAwareInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationAwareList implements IteratorAggregate, LocationAwareListInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add(LocationAwareInterface $item)
    {
        $this->items[$item->getLocation()] = $item;
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

        return new LocationAwareList($filteredList);
    }

    public function count()
    {
        return count($this->items);
    }
}
