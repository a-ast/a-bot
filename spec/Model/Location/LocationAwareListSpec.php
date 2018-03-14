<?php

namespace spec\App\Model\Location;

use App\Model\Location\LocationAwareList;
use App\Model\LocationAwareInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin LocationAwareList
 */
class LocationAwareListSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LocationAwareList::class);
    }

    function it_updates_locations(
        LocationAwareInterface $la1,
        LocationAwareInterface $la2,
        LocationAwareInterface $la3)
    {
        $la1->getLocation()->willReturn('1:1');
        $la2->getLocation()->willReturn('2:2');
        $la3->getLocation()->willReturn('3:3');

        $this->add($la1, 1);
        $this->add($la2, 2);
        $this->add($la3, 3);

        $la2->getLocation()->willReturn('8:8');
        $this->updateLocations();
        $this->getLocations()->shouldReturn(['1:1', '8:8', '3:3']);
    }
}
