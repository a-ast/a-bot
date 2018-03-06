<?php

namespace App\PathFinder;

use App\Model\Location\LocationInterface;
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

    public function initialize(LocationMatrixInterface $locationMatrix, array $context = [])
    {
        $this->locations = $locationMatrix->getKeys();
        $this->size = count($this->locations);

        $this->prepareAdjacentDistances($locationMatrix);
        $this->calculateDistances();

    }

    public function getDistance(LocationInterface $fromLocation, LocationInterface $toLocation): int
    {
        // @todo: find better way
        $from = $fromLocation->getX().':'.$fromLocation->getY();
        $to = $toLocation->getX().':'.$toLocation->getY();

        $i = array_search($from, $this->locations);
        $j = array_search($to, $this->locations);

        return $this->distances[$i][$j];
    }

    public function getNextLocation(LocationInterface $fromLocation, LocationInterface $toLocation): LocationInterface
    {
        // @todo: find better way
        $from = $fromLocation->getX().':'.$fromLocation->getY();
        $to = $toLocation->getX().':'.$toLocation->getY();

        return $this->next[$from][$to];
    }

    private function prepareAdjacentDistances(LocationMatrixInterface $locationMatrix)
    {
        $size = $this->size;

        for ($i = 0; $i < $size; $i++) {
            $this->distances[$i][$i] = 0;
        }

        for ($i = 0; $i < $size; $i++) {

            for ($j = $i + 1; $j < $size; $j++) {

                $iLoc = $this->locations[$i];
                $jLoc = $this->locations[$j];
//                $isNear = $locationMatrix->getLocationByKey($iLoc)
//                    ->isNear($locationMatrix->getLocationByKey($jLoc));

                $isNear = $locationMatrix->isNear($iLoc, $jLoc);

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
