<?php

namespace App\Command;

use App\Game\GameBuilder;
use App\Model\Game\Game;
use App\Model\Location\Location;
use App\Strategy\StrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestGameCommand extends Command
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var \App\Game\GameBuilder
     */
    private $gameBuilder;

    public function __construct(GameBuilder $gameBuilder, StrategyInterface $strategy)
    {
        parent::__construct();

        $this->gameBuilder = $gameBuilder;
        $this->strategy = $strategy;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mapData = file(__DIR__ . '/Map/m5.map', FILE_IGNORE_NEW_LINES);

        $maxWidth = max(array_map('strlen', $mapData));
        $mapData = array_map(function($item) use ($maxWidth) { return str_pad($item, $maxWidth); }, $mapData);

        $game = new Game();
        $this->gameBuilder->buildObjects($game, $mapData);

        $this->strategy->initialize($game->getGamePlay());

        $hero = $game->getHero();
        $hero->setLocation('0:0');
        $hero->setLifePoints(100);
        $hero->setGoldPoints(0);

        $turns = 10;
        $currentTurn = 0;


        do {

            $next = $this->strategy->getNextLocation();

            if (false === $game->getGamePlay()->isGameObjectAt($next)) {
                $hero->setLocation($next);
            }

            $currentTurn++;

        } while ($currentTurn <= $turns);
    }
}
