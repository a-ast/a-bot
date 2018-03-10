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

    public function dump(string $title)
    {
        $text = 'Priorities for [' . $title . ']:' .PHP_EOL;

        foreach ($this->items as $location => $priority) {
            $text .= sprintf('    %s - %d', $location, $priority) . PHP_EOL;
        }

        print $text;
    }
}
