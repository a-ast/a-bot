<?php

namespace App\Command;

use App\Game\TournamentGame;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class RunMultiHeroArenaCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('a-bot:multi')
            ->addArgument('bot-api-key-with-bot-count', InputArgument::IS_ARRAY)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = $input->getArgument('bot-api-key-with-bot-count');

        $processes = [];

        foreach ($keys as $key) {

            list($apiKey, $botCount) = explode(':', $key);

            for ($i = 0; $i < $botCount; $i++) {
                $process = new Process($this->getProcessCli($apiKey));
                $processes[] = $process;
            }
        }

        foreach ($processes as $process) {
            $process->start();
            $output->writeln($process->getPid());
        }

        do {

            $runningCount = 0;
            foreach ($processes as $process) {
                $runningCount += (int)$process->isRunning();
            }

        } while ($runningCount > 0);

    }

    private function getProcessCli(string $apiKey)
    {
        return sprintf('bin/console a-bot:arena %s', $apiKey);
    }
}
