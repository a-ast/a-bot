<?php

namespace App\Model\Location;

class LocationPrioritizer
{
    private $items = [];

    public function add(string $location, int $priority)
    {
        $this->items[$location] = $priority;
    }

    public function getWithMaxPriority(): LocationPriorityPair
    {
        arsort($this->items);

        $location = key($this->items);
        $prio = current($this->items);

        return new LocationPriorityPair($location, $prio);
    }

    public function getWithMinPriority(): LocationPriorityPair
    {
        asort($this->items);

        $location = key($this->items);
        $prio = current($this->items);

        return new LocationPriorityPair($location, $prio);
    }

    public function toArray(): array
    {
        return $this->items;
    }
}
