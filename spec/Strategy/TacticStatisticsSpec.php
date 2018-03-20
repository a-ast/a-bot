<?php

namespace spec\App\Strategy;

use App\Strategy\TacticStatistics;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TacticStatisticsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TacticStatistics::class);
    }

    function it_adds_new_stat()
    {
        $this->add('strength', 123);
    }

    function it_returns_stats_as_array()
    {
        $this->add('strength', 123);

        $this->toArray()->shouldBe(['strength' => 123]);
    }
}
