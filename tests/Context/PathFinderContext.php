<?php

namespace App\Tests\Context;

use App\Model\Game\LocationAwareMap;
use App\Model\Location\Location;
use App\Model\Location\LocationMapBuilder;
use App\Model\Tile\GoldMine;
use App\PathFinder\FloydWarshallAlgorithm;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Webmozart\Assert\Assert;

class PathFinderContext implements Context
{
    /**
     * @var \App\PathFinder\PathFinderInterface
     */
    private $pathFinder;

    /**
     * @var \App\Model\Location\LocationMapInterface
     */
    private $map;

    /**
     * @var \App\Model\Game\LocationAwareMapInterface
     */
    private $goals;

    /**
     * @Given there is a map:
     */
    public function thereIsAMap(PyStringNode $string)
    {
        $mapData = $string->getRaw();
        $builder = new LocationMapBuilder();
        $this->map = $builder->buildFromTextWithEol($mapData);
    }

    /**
     * @Given the map has the goal at :coordinates
     */
    public function theMapHasTheGoalAt($coordinates)
    {
        if (null === $this->goals) {
            $this->goals = new LocationAwareMap();
        }

        $this->goals->add(new GoldMine(Location::fromCoordinates($coordinates)));
    }

    /**
     * @Then the distance from :from to :to is :distance
     */
    public function theDistanceIs($from, $to, $distance)
    {
        $this->initialize();

        $fromLocation = Location::fromCoordinates($from);
        $toLocation = Location::fromCoordinates($to);

        $pathDistance = $this->pathFinder->getDistance($fromLocation, $toLocation);

        Assert::eq($pathDistance, $distance);
    }

    private function initialize()
    {
        if (null !== $this->pathFinder) {
            return;
        }

        $this->pathFinder = new FloydWarshallAlgorithm();
        $this->pathFinder->initialize($this->map, $this->goals);
    }
}
