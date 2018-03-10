<?php

namespace App\Model\Location;

use App\Model\LocationGraphInterface;

class LocationGraph implements LocationGraphInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $edges = [];

    public function add(int $x, int $y): string
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

        return $location;
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

    /**
     * @return string[]
     */
    public function getLocations(): array
    {
        return array_keys($this->items);
    }

    public function isNear(string $from, string $to): bool
    {
        $near = $this->getNearLocations($from);

        return in_array($to, $near);
    }
}
