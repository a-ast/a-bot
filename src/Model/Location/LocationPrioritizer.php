<?php

namespace App\Model\Location;

class LocationPrioritizer
{
    private $items = [];

    public function add(string $location, int $priority)
    {
        $this->items[$priority] = $location;
    }

    public function getWithMaxPriority(): string
    {
        $max = max(array_keys($this->items));

        return $this->items[$max];
    }
}
