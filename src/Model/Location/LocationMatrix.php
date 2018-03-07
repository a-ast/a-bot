<?php

namespace App\Model\Location;

use App\Model\LocationInterface;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class LocationMatrix implements IteratorAggregate, LocationMatrixInterface
{
    private $matrix = [];

    public function addLocation(LocationInterface $location)
    {
        $this->matrix[$location->getCoordinates()] = $location;
    }

//    public function getLocation(int $x, int $y): LocationInterface
//    {
//        return $this->matrix[$this->getKey($x, $y)];
//    }

//    public function getLocationByKey(string $key): LocationInterface
//    {
//        return $this->matrix[$key];
//    }

    public function isNear($iLoc, $jLoc)
    {
        // @todo: fix it

        $a = explode(':', $iLoc);
        $b = explode(':', $jLoc);

        return 1 === (abs($a[0] - $b[0]) + abs($a[1] - $b[1]));
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

    public function getCoordinates(): array
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
