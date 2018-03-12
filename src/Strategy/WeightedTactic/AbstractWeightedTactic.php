<?php

namespace App\Strategy\WeightedTactic;

use App\Exceptions\StrategyException;
use App\Model\Location\LocationAwareListInterface;
use App\Model\GameInterface;
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

    protected function getClosestLocationWithDistance(string $location,
        LocationAwareListInterface $goals): LocationPriorityPair
    {
        if (0 === count($goals)) {
            throw new StrategyException('There are no goals for prioritization');
        }

        $prioritizer = new LocationPrioritizer();

        foreach ($goals as $item) {

            $distance = $this->pathFinder->getDistance(
                $location, $item->getLocation());
            $prioritizer->add($item->getLocation(), $distance);
        }

        $pair = $prioritizer->getWithMinPriority();

        return $pair;
    }

}
