<?php

namespace App\Model\Location;

class LocationTrace
{
    private $locations = [];

    public function add(string $location)
    {
        $this->locations[] = $location;
    }

    public function isRepetitive(int $traceCount, int $uniqueTraceCount)
    {
        if (count($this->locations) < $traceCount) {
            return false;
        }

        $trace = array_slice($this->locations, -1*$traceCount, $traceCount);
        $uniqueTrace = array_unique($trace);

        return count($uniqueTrace) <= $uniqueTraceCount;
    }
}
