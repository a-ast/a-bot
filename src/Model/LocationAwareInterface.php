<?php

namespace App\Model;

use App\Model\LocationInterface;

interface LocationAwareInterface
{
    public function getLocation(): LocationInterface;
}
