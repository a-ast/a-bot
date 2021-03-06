<?php

namespace App\Tests\Context;

use App\Model\Game\GoldMine;
use App\Model\LocationAwareListInterface;
use App\Model\Location\LocationAwareList;
use App\Model\Location\LocationGraphBuilder;
use App\Model\LocationGraphInterface;
use App\PathFinder\FloydWarshallAlgorithm;
use App\PathFinder\PathFinderInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Webmozart\Assert\Assert;

class PathFinderContext implements Context
{
    /**
     * @var PathFinderInterface
     */
    private $pathFinder;

    /**
     * @var LocationGraphInterface
     */
    private $graph;

    /**
     * @var LocationAwareListInterface
     */
    private $goals;

    /**
     * @Given there is a map:
     */
    public function thereIsAMap(PyStringNode $string)
    {
        $mapData = $string->getRaw();
        $builder = new LocationGraphBuilder();
        $this->graph = $builder->buildFromTextWithEol($mapData);
    }

    /**
     * @Given the map has the goal at :location
     */
    public function theMapHasTheGoalAt($location)
    {
        if (null === $this->goals) {
            $this->goals = new LocationAwareList();
        }

        $this->goals->add(new GoldMine($location));
    }

    /**
     * @Then the distance from :from to :to is :distance
     */
    public function theDistanceIs($from, $to, $distance)
    {
        $this->initialize();

        $pathDistance = $this->pathFinder->getDistance($from, $to);

        Assert::eq($pathDistance, $distance);
    }

    /**
     * @Then the path from :from to :to is :path
     */
    public function thePathFromToIs($from, $to, $path)
    {
        $this->initialize();

        $pathParts = [];

        while ($from !== $to) {

            $pathNext = $this->pathFinder->getNextLocation($from, $to);
            $pathParts[] = $pathNext;

            $from = $pathNext;
        }

        $actualPath = join('->', $pathParts);

        Assert::eq($path, $actualPath);
    }

    private function initialize()
    {
        if (null !== $this->pathFinder) {
            return;
        }

        $this->pathFinder = new FloydWarshallAlgorithm();
        $this->pathFinder->initialize($this->graph,
            $this->goals ? $this->goals->getLocations() : []);
    }
}
