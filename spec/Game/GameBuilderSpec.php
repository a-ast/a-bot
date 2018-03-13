<?php

namespace spec\App\Game;

use App\Game\GameBuilder;
use App\Model\Game\Game;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GameBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GameBuilder::class);
    }

    function it_builds_game_from_string()
    {
        $this->buildGameFromText('######      ######', 3)->shouldHaveType(Game::class);
    }
}
