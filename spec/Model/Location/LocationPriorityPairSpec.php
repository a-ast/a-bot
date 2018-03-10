<?php

namespace spec\App\Model\Location;

use App\Model\Location\LocationPriorityPair;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationPriorityPairSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('1:1', 86);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LocationPriorityPair::class);
    }

    function it_gets_location()
    {
        $this->getLocation()->shouldReturn('1:1');
    }

    function it_gets_priority()
    {
        $this->getPriority()->shouldReturn(86);
    }
}
