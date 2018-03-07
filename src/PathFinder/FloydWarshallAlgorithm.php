<?php

namespace App\PathFinder;

use App\Model\Location\Location;
use App\Model\LocationInterface;
use App\Model\Location\LocationMatrixInterface;

class FloydWarshallAlgorithm implements PathFinderInterface
{

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
     * @var int
     */
    private $size;

    /**
     * @var array
     */
    private $locationIndexes;

    public function initialize(LocationMatrixInterface $locationMatrix, array $context = [])
    {
        $this->locations = $locationMatrix->getCoordinates();
        $this->locationIndexes = array_flip($this->locations);

        $this->size = count($this->locations);

        $this->prepareAdjacentDistances($locationMatrix);
        $this->calculateDistances();

        // @todo: calculate to goals

    }

    public function getDistance(LocationInterface $fromLocation, LocationInterface $toLocation): int
    {
        $i = $this->locationIndexes[$fromLocation->getCoordinates()];
        $j = $this->locationIndexes[$toLocation->getCoordinates()];

        return $this->distances[$i][$j];
    }

    public function getNextLocation(LocationInterface $fromLocation, LocationInterface $toLocation): LocationInterface
    {
        $i = $this->locationIndexes[$fromLocation->getCoordinates()];
        $j = $this->locationIndexes[$toLocation->getCoordinates()];

        return $this->next[$i][$j];
    }

    private function prepareAdjacentDistances(LocationMatrixInterface $locationMatrix)
    {
        $size = $this->size;

        for ($i = 0; $i < $size; $i++) {
            $this->distances[$i][$i] = 0;
        }

        for ($i = 0; $i < $size; $i++) {

            for ($j = $i + 1; $j < $size; $j++) {

                $iLoc = Location::fromCoordinates($this->locations[$i]);
                $jLoc = Location::fromCoordinates($this->locations[$j]);

                $isNear = $iLoc->isNear($jLoc);

                if ($isNear) {
                    $this->distances[$i][$j] = 1;
                    $this->distances[$j][$i] = 1;
                    $this->next[$i][$j] = $jLoc;
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
}
