<?php

namespace App\Model\Location;

class LocationGraph
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $edges = [];

    public function add(int $x, int $y)
    {
        $location = Location::getLocation($x, $y);
        $this->items[$location] = 1;

        $neighbours = [[-1, 0], [0, -1]];

        foreach ($neighbours as $neighbour) {

            $neighbourLocation = Location::getLocation($x + $neighbour[0],
                $y + $neighbour[1]);

            if (isset($this->items[$neighbourLocation])) {
                $this->edges[$neighbourLocation][] = $location;
                $this->edges[$location][] = $neighbourLocation;
            }
        }
    }

    /**
     * @return string[]
     */
    public function getNearLocations(string $location): array
    {
        if (!isset($this->edges[$location])) {
            return [];
        }

        return $this->edges[$location];
    }
}
