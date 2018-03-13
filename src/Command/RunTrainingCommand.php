<?php

namespace App\Command;

use App\Game\TournamentGame;
use App\Strategy\StrategyProvider;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTrainingCommand extends Command
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
            ->setName('a-bot:train')
            ->addArgument('api-key', InputArgument::REQUIRED)
            ->addArgument('strategy', InputArgument::REQUIRED)
            ->addArgument('turn-count', InputArgument::OPTIONAL, '', null)
            ->addArgument('map', InputArgument::OPTIONAL, '', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $strategyAlias = $input->getArgument('strategy');
        $this->game->setStrategy($this->strategyProvider->getByAlias($strategyAlias));

        try {
            $this->game->executeTraining(
              $input->getArgument('api-key'),
              $input->getArgument('turn-count'),
              $input->getArgument('map')
            );
        } catch (Exception $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));

            return $exception->getCode();
        }
    }
}
