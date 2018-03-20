<?php

namespace App\Model\Location;

class LocationPrioritizer
{
    private $items = [];

    private const SORT_DESC = 'desc';
    private const SORT_ASC = 'asc';
    private const SORT_NONE = 'none';

    /**
     * @var bool
     */
    private $sortDirection = self::SORT_NONE;

    public function add(string $location, int $priority)
    {
        $this->items[$location] = $priority;
    }

    public function getWithMaxPriority(): LocationPriorityPair
    {
        $this->sortDesc();

        $location = key($this->items);
        $prio = current($this->items);

        return new LocationPriorityPair($location, $prio);
    }

    /**
     * @throws \Exception
     */
    public function getNextAfterMax(): LocationPriorityPair
    {
        if (count($this->items) < 2) {
            throw new \Exception('Number of locations must be more than 1 to get second item.');
        }

        $this->sortDesc();

        $locations = array_keys($this->items);
        $location = $locations[1];
        $prio = $this->items[$location];

        return new LocationPriorityPair($location, $prio);
    }

    public function getWithMinPriority(): LocationPriorityPair
    {
        $this->sortAsc();

        $location = key($this->items);
        $prio = current($this->items);

        return new LocationPriorityPair($location, $prio);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    private function sortDesc(): void
    {
        if ($this->sortDirection === self::SORT_DESC) {
            return;
        }

        arsort($this->items);

        $this->sortDirection = self::SORT_DESC;
    }

    private function sortAsc(): void
    {
        if ($this->sortDirection === self::SORT_ASC) {
            return;
        }

        asort($this->items);

        $this->sortDirection = self::SORT_ASC;
    }
}
