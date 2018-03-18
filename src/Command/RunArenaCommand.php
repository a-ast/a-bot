<?php

namespace App\Command;

use App\Game\TournamentGame;
use App\Strategy\StrategyProvider;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunArenaCommand extends Command
{
    /**
     * @var TournamentGame
     */
    private $game;

    /**
     * @var StrategyProvider
     */
    private $strategyProvider;

    public function __construct(TournamentGame $game, StrategyProvider $strategyProvider)
    {
        parent::__construct();

        $this->game = $game;
        $this->strategyProvider = $strategyProvider;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:arena')
            ->addArgument('api-key', InputArgument::REQUIRED)
            ->addArgument('strategy', InputArgument::REQUIRED)
            ->addOption('dump', 'd', InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strategyAlias = $input->getArgument('strategy');
        $this->game->setStrategy($this->strategyProvider->getByAlias($strategyAlias));

//        try {

            $dumpEnabled = $input->getOption('dump');

            $this->game->executeArena($input->getArgument('api-key'), $dumpEnabled);
//        } catch (Exception $exception) {
//            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
//
//            return $exception->getCode();
//        }
    }
}
