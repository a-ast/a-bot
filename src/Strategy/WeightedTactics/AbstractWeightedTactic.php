<?php

namespace App\Strategy\WeightedTactics;

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

    protected function getDistanceToGoal(string $from, LocationAwareInterface $goal, bool $isFallbackToHeroLocation): int
    {
        $distance = $this->pathFinder->getDistance($from, $goal->getLocation());

        // if it fall backs and distance is 1,
        // then the actual distance is 0
        if (1 === $distance && $isFallbackToHeroLocation) {
            return 0;
        }

        // if it is another object,
        // then distance will take one more step
        if ($isFallbackToHeroLocation) {
            $distance++;
        }

        return $distance;
    }

    protected function getBalancedWeightFromDistance(int $distance): float
    {
        // this is experimental value
        $k = 0.5;

        return 1000 * (1 / ($k * ($distance + 1)));
        // from finding week heroes is 1000 * $k * (1 / ($distanceToGoal + 1));
    }
}
