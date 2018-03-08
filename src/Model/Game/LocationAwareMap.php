<?php

namespace App\Model\Game;

use App\Model\LocationAwareInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationAwareMap implements IteratorAggregate, LocationAwareMapInterface
{
    /**
     * @var array
     */
    private $items = [];

    public function add(LocationAwareInterface $item)
    {
        $this->items[$item->getLocation()->getCoordinates()] = $item;
    }

    public function getCoordinateList(): array
    {
        return array_keys($this->items);
    }

    public function getByCoordinates(string $coordinates): LocationAwareInterface
    {
        return $this->items[$coordinates];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
