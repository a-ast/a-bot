<?php


namespace App\Command;


use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\Model\Location\Location;
use App\Model\Location\LocationMapInterface;
use App\Model\Location\LocationMapBuilder;
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

        $from = new Location(0, 0);
        $to = new Location(5, 2);

        $pathFinder = new FloydWarshallAlgorithm();

        $watch = new Stopwatch(false);

        $goldMines = $board->getGoldMines();
        $taverns = $board->getTaverns();
        $goals = $goldMines->addMap($taverns);

        $watch->start('init path finder');
        $pathFinder->initialize($board->getMap(), $goals);
        $watchResult = $watch->stop('init path finder');


        $output->writeln('Duration: '.$watchResult->getDuration());
        $output->writeln('Memory: '.$watchResult->getMemory() / (1024));

        foreach ($board->getMap() as $from) {

            foreach ($goals as $goal) {
                if ($goal->getLocation() === $from) {
                    continue(2);
                }
            }

            foreach ($board->getMap() as $to) {
                $pathDistance = $pathFinder->getDistance($from, $to);
                $next = $pathFinder->getNextLocation($from, $to);
            }
        }


//
//
//        foreach ($path as $item) {
//            $output->writeln(sprintf('%d:%d', $item->getX(), $item->getY()));
//        }


    }
}
