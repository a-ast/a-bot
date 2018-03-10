<?php

namespace App\Model\Location;

class LocationPrioritizer
{
    private $items = [];

    public function add(string $location, int $priority)
    {
        $this->items[$priority] = $location;
    }

    public function getWithMaxPriority(): LocationPriorityPair
    {
        $max = max(array_keys($this->items));

        return new LocationPriorityPair($this->items[$max], $max);
    }

    public function getWithMinPriority(): LocationPriorityPair
    {
        $min = min(array_keys($this->items));

        return new LocationPriorityPair($this->items[$min], $min);
    }
}
