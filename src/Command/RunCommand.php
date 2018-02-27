<?php


namespace App\Command;


use App\Game\TrainingGame;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var TrainingGame
     */
    private $game;

    public function __construct(TrainingGame $game)
    {
        parent::__construct();

        $this->game = $game;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:train')
            ->addArgument('bot-api-key', InputArgument::REQUIRED)
            ->addArgument('turn-count', InputArgument::OPTIONAL, '', null)
            ->addArgument('map', InputArgument::OPTIONAL, '', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->game->execute(
              $input->getArgument('bot-api-key'),
              $input->getArgument('turn-count'),
              $input->getArgument('map')
            );
        } catch (Exception $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));

            return $exception->getCode();
        }
    }
}