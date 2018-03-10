<?php

namespace App\Model;

interface LocationAwareInterface
{
    public function getLocation(): string;
}
