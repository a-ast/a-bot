<?php

namespace App\Model\Location;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationMatrix implements IteratorAggregate, LocationMatrixInterface
{
    private $matrix = [];

    public function addLocation(LocationInterface $location)
    {
        $this->matrix[$this->getKeyFromLocation($location)] = $location;
    }

    public function getLocation(int $x, int $y): LocationInterface
    {
        return $this->matrix[$this->getKey($x, $y)];
    }

    public function getLocationByKey(string $key): LocationInterface
    {
        return $this->matrix[$key];
    }

    private function getKeyFromLocation(LocationInterface $location): string
    {
        return $location->getX() . ':' . $location->getY();
    }

    private function getKey(int $x, int $y): string
    {
        return $x . ':' . $y;
    }

    /**
     * @return Traversable|LocationInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->matrix);
    }

    public function getKeys(): array
    {
        return array_keys($this->matrix);
    }
}
