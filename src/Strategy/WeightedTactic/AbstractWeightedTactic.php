<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\LocationAwareInterface;
use App\Model\LocationAwareListInterface;
use App\Model\Location\LocationPrioritizer;
use App\Model\Location\LocationPriorityPair;
use App\PathFinder\PathFinderInterface;

abstract class AbstractWeightedTactic implements WeightedTacticInterface
{
    /**
     * @var \App\PathFinder\PathFinderInterface
     */
    private $pathFinder;

    public function __construct(PathFinderInterface $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    /**
     * @throws \App\Exceptions\StrategyException
     */
    protected function getClosestLocationWithDistance(string $location,
        LocationAwareListInterface $goals): LocationPriorityPair
    {
        if (0 === count($goals)) {
            throw new StrategyException('There are no goals for prioritization');
        }

        $prioritizer = new LocationPrioritizer();

        foreach ($goals->getLocations() as $goalLocation) {

            $distance = $this->pathFinder->getDistance($location, $goalLocation);
            $prioritizer->add($goalLocation, $distance);
        }

        $pair = $prioritizer->getWithMinPriority();

        return $pair;
    }

    protected function getDistanceToGoal(string $from, LocationAwareInterface $goal): int
    {
        return $this->pathFinder->getDistance($from, $goal->getLocation());
    }
}
