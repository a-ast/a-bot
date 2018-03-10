<?php


namespace App\Command;


use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\Model\Location\Location;
use App\Model\Location\LocationGraphInterface;
use App\Model\Location\LocationGraphBuilder;
use App\Model\Location\Road;
use App\Model\Location\Wall;
use App\PathFinder\FloydWarshallAlgorithm;
use App\PathFinder\LeeAlgorithm;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;

class WatchPathFinderCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('a-bot:path')
            ->addArgument('map-name', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = sprintf('/Map/%s.map', $input->getArgument('map-name'));
        $mapData = file(__DIR__ . $fileName, FILE_IGNORE_NEW_LINES);

        $maxWidth = max(array_map('strlen', $mapData));
        $mapData = array_map(function($item) use ($maxWidth) { return str_pad($item, $maxWidth); }, $mapData);

        $board = new Board($maxWidth / 2, join('', $mapData));

        $pathFinder = new FloydWarshallAlgorithm();

        $watch = new Stopwatch(false);

        $watch->start('init path finder');
        $pathFinder->initialize($board->getMap(), $board->getGoalLocations());
        $watchResult = $watch->stop('init path finder');


        $output->writeln('Duration: '.$watchResult->getDuration());
        $output->writeln('Memory: '.$watchResult->getMemory() / (1024));

        foreach ($board->getWalkableLocations() as $from) {

            foreach ($board->getMap()->getLocations() as $to) {
                $pathDistance = $pathFinder->getDistance($from, $to);

                if ($from !== $to) {
                    $next = $pathFinder->getNextLocation($from, $to);
                }
            }
        }
    }
}
