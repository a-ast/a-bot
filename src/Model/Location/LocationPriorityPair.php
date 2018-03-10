<?php

namespace App\Model\Location;

class LocationPriorityPair
{

    /**
     * @var string
     */
    private $location;

    /**
     * @var int
     */
    private $priority;

    public function __construct(string $location, int $priority)
    {
        $this->location = $location;
        $this->priority = $priority;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getPriority()
    {
        return $this->priority;
    }
}
