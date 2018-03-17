<?php

namespace App\Model\Location;

trait LocationAwareTrait
{
    /**
     * @var string
     */
    private $location = '';

    public function __construct(string $location)
    {
        $this->location = $location;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

}
