<?php

namespace App\Command;

use App\Game\GameBuilder;
use App\Game\GameLoader;
use App\Model\Game\Game;
use App\Model\Game\Hero;
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
     * @var GameLoader
     */
    private $gameLoader;

    public function __construct(GameLoader $gameLoader, StrategyInterface $strategy)
    {
        parent::__construct();

        $this->gameLoader = $gameLoader;
        $this->strategy = $strategy;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:test')
            ->addArgument('path')
            ->addArgument('turn')
        ;
    }

    /**
     * @throws \App\Exceptions\GameException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $game = $this->gameLoader->loadFromFile($input->getArgument('path'), $input->getArgument('turn'));
        $this->strategy->initialize($game->getGamePlay());

        $next = $this->strategy->getNextLocation();

        print $next;
    }
}
