<?php

namespace App\Command;

use App\Game\TournamentGame;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunArenaCommand extends Command
{
    /**
     * @var TournamentGame
     */
    private $game;

    public function __construct(TournamentGame $game)
    {
        parent::__construct();

        $this->game = $game;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:arena')
            ->addArgument('bot-api-key', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->game->executeArena($input->getArgument('bot-api-key'));
        } catch (Exception $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));

            return $exception->getCode();
        }
    }
}