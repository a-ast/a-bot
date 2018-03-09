<?php

namespace App\Model\Location;

use App\Model\LocationInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationMap implements IteratorAggregate, LocationMapInterface
{
    private $matrix = [];

    public function add(LocationInterface $location)
    {
        $this->matrix[$location->getCoordinates()] = $location;
    }

    public function getNearLocations(LocationInterface $location): array
    {
        $x = $location->getX();
        $y = $location->getY();

        $directions = [
            [ -1,  0],
            [  1,  0],
            [  0, -1],
            [  0,  1],
        ];

        $near = [];

        foreach ($directions as $direction) {

            $key = (new Location($x + $direction[0], $y + $direction[1]))->getCoordinates();
            if (isset($this->matrix[$key])) {
                $near[] = $this->matrix[$key];
            }
        }

        return $near;
    }

    public function getCoordinatesList(): array
    {
        return array_keys($this->matrix);
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
