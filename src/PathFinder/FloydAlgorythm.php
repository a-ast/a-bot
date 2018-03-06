<?php

namespace App\PathFinder;

use App\Model\Location\LocationInterface;
use App\Model\Location\LocationMatrixInterface;

class FloydAlgorythm implements PathFinderInterface
{
    const INF = 100000;

    private $distances = [];

    private $next = [];

    public function initialize(LocationMatrixInterface $locationMatrix, array $context = [])
    {
        $locations = $locationMatrix->getKeys();
        $size = count($locations);

        // step 1

        $this->prepareAdjacentDistances($locationMatrix, $size, $locations);

        print "Init matrix finished\n";

        $this->calculateDistances($size, $locations);

        print "Calc matrix finished\n";
    }

    public function getDistance(LocationInterface $fromLocation, LocationInterface $toLocation): int
    {
        // @todo: find better way
        $from = $fromLocation->getX().':'.$fromLocation->getY();
        $to = $toLocation->getX().':'.$toLocation->getY();

        return $this->distances[$from][$to];
    }

    public function getNextLocation(LocationInterface $fromLocation, LocationInterface $toLocation): LocationInterface
    {
        // @todo: find better way
        $from = $fromLocation->getX().':'.$fromLocation->getY();
        $to = $toLocation->getX().':'.$toLocation->getY();

        return $this->next[$from][$to];
    }

    private function prepareAdjacentDistances(LocationMatrixInterface $locationMatrix, $size, &$locations)
    {
        for ($i = 0; $i < $size; $i++) {
            $this->distances[$locations[$i]][$locations[$i]] = 0;
        }

        for ($i = 0; $i < $size; $i++) {

            for ($j = $i + 1; $j < $size; $j++) {

                $iLoc = $locations[$i];
                $jLoc = $locations[$j];
//                $isNear = $locationMatrix->getLocationByKey($iLoc)
//                    ->isNear($locationMatrix->getLocationByKey($jLoc));

                $isNear = $locationMatrix->isNear($iLoc, $jLoc);

                if ($isNear) {
                    $this->distances[$iLoc][$jLoc] = 1;
                    $this->distances[$jLoc][$iLoc] = 1;
                    $this->next[$iLoc][$jLoc] = $jLoc;
                }

            }
        }
    }

    private function calculateDistances($size, &$locations): void
    {
        for ($k = 0; $k < $size; $k++) {

            $kLoc = $locations[$k];

            for ($i = 0; $i < $size; $i++) {

                if ($i === $k) {
                    continue;
                }

                $iLoc = $locations[$i];

                if (!isset($this->distances[$iLoc][$kLoc])) {
                    continue;
                }

                for ($j = 0; $j < $i; $j++) {

                    if ($j === $k) {
                        continue;
                    }

                    $jLoc = $locations[$j];

                    if (!isset($this->distances[$kLoc][$jLoc])) {
                        continue;
                    }

                    $d = $this->distances[$iLoc][$kLoc] + $this->distances[$kLoc][$jLoc];
                    if (
                        !isset($this->distances[$iLoc][$jLoc]) ||
                        $d < $this->distances[$iLoc][$jLoc]) {

                        $this->distances[$iLoc][$jLoc] = $this->distances[$jLoc][$iLoc] = $d;

                        $this->next[$iLoc][$jLoc] = $this->next[$iLoc][$kLoc] ?? null;
                        $this->next[$jLoc][$iLoc] = $this->next[$jLoc][$kLoc] ?? null;
                    }

                }
            }
        }
    }

}
