<?php

namespace spec\App\Model\Location;

use App\Model\Location\LocationTrace;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocationTraceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LocationTrace::class);
    }

    function it_adds_new_locations()
    {
        $this->add('1:1');

        $this->getLast()->shouldBe('1:1');
    }

    function it_returns_true_if_trace_is_repetitive()
    {
        $this->add('0:0');
        $this->add('0:1');
        // 6 repetitive steps
        $this->add('1:1');
        $this->add('2:2');
        $this->add('1:1');
        $this->add('2:2');
        $this->add('1:1');
        $this->add('2:2');

        $this->isRepetitive(6, 2)->shouldBe(true);
    }

    function it_returns_false_if_trace_is_not_repetitive()
    {
        $this->add('0:0');
        $this->add('0:1');
        // 6 non-repetitive steps
        $this->add('1:1');
        $this->add('2:2');
        $this->add('3:3');
        $this->add('4:4');
        $this->add('5:5');
        $this->add('6:6');

        $this->isRepetitive(6, 2)->shouldBe(false);
    }

    function it_returns_false_if_trace_is_not_repetitive_and_trace_is_short()
    {
        $this->add('5:5');
        $this->add('6:6');

        $this->isRepetitive(6, 2)->shouldBe(false);
    }

    function it_replaces_last_location()
    {
        $this->add('0:0');
        $this->add('0:1');
        $this->add('1:1');

        $this->replaceLast('2:2');

        $this->getLast()->shouldBe('2:2');
    }
}
