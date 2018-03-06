<?php

namespace App\Tests\Context;

use App\Model\Location\LocationMatrixBuilder;
use App\PathFinder\FloydAlgorythm;
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
     * @var \App\Model\Location\LocationMatrixInterface
     */
    private $matrix;

    /**
     * @Given there is a map:
     */
    public function thereIsAMap(PyStringNode $string)
    {
        $mapData = $string->getRaw();
        $builder = new LocationMatrixBuilder();
        $this->matrix = $builder->buildFromTextWithEol($mapData);

        $this->pathFinder = new FloydAlgorythm();
        $this->pathFinder->initialize($this->matrix);
    }

    /**
     * @Then the distance from :from to :to is :distance
     */
    public function theDistanceFromToIs($from, $to, $distance)
    {
        $fromLocation = $this->matrix->getLocationByKey($from);
        $toLocation = $this->matrix->getLocationByKey($to);

        $pathDistance = $this->pathFinder->getDistance($fromLocation, $toLocation);


        Assert::eq($pathDistance, $distance);

    }
}
