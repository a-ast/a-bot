<?php

namespace App\PathFinder;

use App\Model\Location\LocationAwareListInterface;
use App\Model\LocationGraphInterface;

class FloydWarshallAlgorithm implements PathFinderInterface
{
    const INF = 10000;

    /**
     * @var array
     */
    private $distances = [];

    /**
     * @var array
     */
    private $next = [];

    /**
     * @var array
     */
    private $locations;

    /**
     * @var LocationAwareListInterface
     */
    private $goals;

    /**
     * @var int
     */
    private $size;

    /**
     * @var array
     */
    private $locationIndexes;

    /**
     * @var LocationGraphInterface
     */
    private $graph;

    /**
     * @var array
     *
     * Coordinates of goals
     */
    private $goalLocations;

    public function initialize(LocationGraphInterface $locationGraph,
        array $goalLocations = [], array $context = [])
    {
        $this->graph = $locationGraph;
        $this->goalLocations = $goalLocations;

        $this->locations = array_values(array_diff($locationGraph->getLocations(),
            $this->goalLocations
        ));

        $this->locationIndexes = array_flip($this->locations);
        $this->size = count($this->locations);

        $this->prepareAdjacentDistances();
        $this->calculateDistances();

        if (count($this->goalLocations) > 0) {
            $this->calculateDistancesForGoals();
        }
    }

    public function getDistance(string $from, string $to): int
    {
        if ($from === $to) {
            return 0;
        }

        $i = $this->locationIndexes[$from];
        $j = $this->locationIndexes[$to];

        if (!isset($this->distances[$i][$j])) {
            return self::INF;
        }

        return $this->distances[$i][$j];
    }

    public function getNextLocation(string $from, string $to): string
    {
        $i = $this->locationIndexes[$from];
        $j = $this->locationIndexes[$to];

        if (!isset($this->next[$i][$j])) {
            print sprintf('SOS! Unknown next step from %s to %s', $from, $to) . PHP_EOL;
        }

        $next = $this->next[$i][$j];

        return $this->locations[$next];
    }

    private function prepareAdjacentDistances()
    {
        $size = $this->size;

        for ($i = 0; $i < $size; $i++) {
            $this->distances[$i][$i] = 0;
        }

        for ($i = 0; $i < $size; $i++) {

            for ($j = $i + 1; $j < $size; $j++) {

                $iLoc = $this->locations[$i];
                $jLoc = $this->locations[$j];

                $isNear = $this->graph->isNear($iLoc, $jLoc);

                if ($isNear) {
                    $this->distances[$i][$j] = 1;
                    $this->distances[$j][$i] = 1;
                    $this->next[$i][$j] = $j;
                    $this->next[$j][$i] = $i;
                }
            }
        }
    }

    private function calculateDistances()
    {
        for ($k = 0; $k < $this->size; $k++) {

            for ($i = 0; $i < $this->size; $i++) {

//                if ($i === $k) {
//                    continue;
//                }

                if (!isset($this->distances[$i][$k])) {
                    continue;
                }

                for ($j = 0; $j < $i; $j++) {

//                    if ($j === $k) {
//                        continue;
//                    }

                    if (!isset($this->distances[$k][$j])) {
                        continue;
                    }

                    $d = $this->distances[$i][$k] + $this->distances[$k][$j];
                    if (
                        !isset($this->distances[$i][$j]) ||
                        $d < $this->distances[$i][$j]) {

                        $this->distances[$i][$j] = $this->distances[$j][$i] = $d;

                        $this->next[$i][$j] = $this->next[$i][$k] ?? null;
                        $this->next[$j][$i] = $this->next[$j][$k] ?? null;
                    }

                }
            }
        }
    }

    private function calculateDistancesForGoals()
    {
        // calculate distances from existing locations to goals
        // it needs new vertical columns for distance array
        $jNew = count($this->distances);

        foreach ($this->goalLocations as $goalLocation) {

            $this->locationIndexes[$goalLocation] = $jNew;
            $this->locations[$jNew] = $goalLocation;

            $nearLocationCoordinates = $this->getNearLocationsButNotGoals($goalLocation);

            for ($i = 0; $i < $this->size; $i++) {

                $sourceCoordinates = $this->locations[$i];


                $minDistance = self::INF;
                $minJIndex = self::INF;

                // find min distance
                foreach ($nearLocationCoordinates as $nearCoordinate) {

                    // this is not road - gold or tavern
                    if (!isset($this->locationIndexes[$nearCoordinate])) {
                        continue(2);
                    }

                    // get index of the column
                    $j = $this->locationIndexes[$nearCoordinate];

                    // skip if the near location is our current i-location
                    if ($j === $i) {
                        $this->distances[$i][$jNew] = 1;
                        $this->next[$i][$jNew] = $jNew;

                        continue(2); // @todo: sure you don't miss smth?
                    }

                    if ($this->distances[$i][$j] < $minDistance) {
                        $minDistance = $this->distances[$i][$j];
                        $minJIndex = $j;
                    }
                }

                // Every goal - new j-column
                $this->distances[$i][$jNew] = $minDistance + 1;
                $this->next[$i][$jNew] = $this->next[$i][$minJIndex];
            }

            $jNew++;
        }
    }

    /**
     * @return string[]
     */
    private function getNearLocationsButNotGoals(string $goal): array
    {
        $nearLocations = $this->graph->getNearLocations($goal);

        $coordinates = array_diff($nearLocations, $this->goalLocations);

        return $coordinates;
    }

}
