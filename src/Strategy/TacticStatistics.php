<?php

namespace App\Strategy;

class TacticStatistics
{
    /**
     * @var array
     */
    private $items = [];

    public function add(string $name, $value): void
    {
        $this->items[$name] = $value;
    }

    public function toArray(): array
    {
        return $this->items;
    }


}
