<?php

namespace App\PathFinder;

use App\Model\Location\Location;
use App\Model\LocationGraphInterface;

class AStarAlgorithm implements PathFinderInterface
{
    /**
     * @var LocationGraphInterface
     */
    private $locationGraph;

    public function initialize(LocationGraphInterface $locationGraph,
        array $goalLocations = [],
        array $context = []
    ) {
        $this->locationGraph = $locationGraph;
    }

    public function getDistance(string $from, string $to): int
    {
        $open = $this->locationGraph->getNearLocations($from);
        $closed = [];

        $finalGraph = [];

        $fromXY = Location::getXY($from);
        $toXY = Location::getXY($to);

        do {

            $frontier = array_diff($open, $closed);

            foreach ($frontier as $frontierLocation) {

                $currentXY = Location::getXY($frontierLocation);

                $g = abs($currentXY[0] - $fromXY[0]) + abs($currentXY[1] - $fromXY[1]);
                $h = abs($currentXY[0] - $toXY[0]) + abs($currentXY[1] - $toXY[1]);

                $finalGraph[$frontierLocation] = [
                        'score' => $g + $h,
                        //'parent' => $finalGraph
                    ];

                $closed = array_merge($closed, [$frontierLocation]);
                $nearLocations = $this->locationGraph->getNearLocations($frontierLocation);
                $open = array_merge($open, $nearLocations);
            }

        } while (count($frontier) > 0);

        var_dump($finalGraph);


    }

    public function getNextLocation(string $from, string $to): string
    {
        // TODO: Implement getNextLocation() method.
    }
}
