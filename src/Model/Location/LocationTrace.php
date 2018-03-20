<?php

namespace App\Model\Location;

class LocationTrace
{
    private $locations = [];

    public function add(string $location)
    {
        $this->locations[] = $location;
    }

    public function isRepetitive(int $traceCount, int $uniqueTraceCount): bool
    {
        if (count($this->locations) < $traceCount) {
            return false;
        }

        $trace = array_slice($this->locations, -1*$traceCount, $traceCount);
        $uniqueTrace = array_unique($trace);

        return count($uniqueTrace) <= $uniqueTraceCount;
    }

    public function getLast(): string
    {
        return $this->locations[$this->count() - 1];
    }

    public function replaceLast(string $location): void
    {
        $this->locations[$this->count() - 1] = $location;
    }

    private function count(): int
    {
        return count($this->locations);
    }
}
