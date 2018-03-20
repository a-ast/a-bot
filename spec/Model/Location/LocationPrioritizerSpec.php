<?php

namespace spec\App\Model\Location;

use App\Model\Location\LocationPrioritizer;
use App\Model\Location\LocationPriorityPair;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationPrioritizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LocationPrioritizer::class);
    }

    function it_adds_location_with_priority()
    {
        $this->add('1:1', 10);
    }

    function it_gets_location_with_max_priority()
    {
        $this->add('1:1', 10);
        $this->add('2:1', 0);
        $this->add('8:7', 80);
        $this->add('4:3', -999);
        $this->add('2:2', 86868686);
        $this->add('1:7', 100);
        $this->add('3:3', -100);

        $pair = $this->getWithMaxPriority();
        $pair->getLocation()->shouldReturn('2:2');
        $pair->getPriority()->shouldReturn(86868686);
    }

    function it_gets_location_with_min_priority()
    {
        $this->add('1:1', 10);
        $this->add('2:1', 0);
        $this->add('8:7', 80);
        $this->add('4:3', -999);
        $this->add('2:2', 86868686);
        $this->add('1:7', 100);
        $this->add('3:3', -100);

        $pair = $this->getWithMinPriority();
        $pair->getLocation()->shouldReturn('4:3');
        $pair->getPriority()->shouldReturn(-999);
    }

    function it_gets_location_next_after_max()
    {
        $this->add('1:1', 10);
        $this->add('2:1', 0);
        $this->add('8:7', 80);
        $this->add('4:3', -999);
        $this->add('2:2', 86868686);
        $this->add('1:7', 100);
        $this->add('3:3', -100);

        $pair = $this->getNextAfterMax();
        $pair->getLocation()->shouldReturn('1:7');
        $pair->getPriority()->shouldReturn(100);
    }
}
