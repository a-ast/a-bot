<?php

namespace App\Tests\Context;

use App\Model\Location\Location;
use App\Model\Location\LocationGraph;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Webmozart\Assert\Assert;

class LocationGraphContext implements Context
{
    /**
     * @var LocationGraph
     */
    private $graph;

    /**
     * @Given the location graph has locations:
     */
    public function theLocationGraphHasNodes(TableNode $locations)
    {
        $this->graph = new LocationGraph();
        
        foreach ($locations->getRows() as $row) {
            foreach ($row as $item) {
                if ('' !== $item) {
                    list($x, $y) = Location::getXY($item);

                    $this->graph->add($x, $y);
                }
            }
        }
    }

    /**
     * @Then adjacent locations of :location are:
     */
    public function adjacentLocationsOfAre($location, TableNode $locations)
    {
        $adjacentLocations = $this->graph->getNearLocations($location);
        $expectedLocations = array_column($locations->getRows(), 0);

        Assert::eq($adjacentLocations, $expectedLocations);
    }

    /**
     * @Then there are no adjacent locations of :location
     */
    public function thereAreNoAdjacentLocationsOf($location)
    {
        $adjacentLocations = $this->graph->getNearLocations($location);

        Assert::count($adjacentLocations, 0);
    }
}
