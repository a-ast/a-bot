<?php

namespace App\Model\Location;

use App\Model\LocationInterface;

trait LocationAwareTrait
{
    /**
     * @var LocationInterface
     */
    private $location;

    public function __construct(LocationInterface $location)
    {
        $this->location = $location;
    }

    public function getLocation(): LocationInterface
    {
        return $this->location;
    }

}
