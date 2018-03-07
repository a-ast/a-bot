<?php


namespace App\Command;


use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\Model\Location\Location;
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
            ->setName('a-bot:path-finder')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mapData = file(__DIR__ . '/Map/m6.map', FILE_IGNORE_NEW_LINES);

        $maxWidth = max(array_map('strlen', $mapData));
        $mapData = array_map(function($item) use ($maxWidth) { return str_pad($item, $maxWidth); }, $mapData);

        $board = new Board($maxWidth / 2, join('', $mapData));

        $from = new Location(0, 0);
        $to = new Location(8, 8);

        $goldMines = $board->getGoldMines();
        $gold1 = $goldMines[0];

        //$pathFinder = new LeeAlgorythm();
        $pathFinder = new FloydWarshallAlgorithm();

        $watch = new Stopwatch(false);
        $watch->start('path');

        $pathFinder->initialize($board, [$to, $gold1]);

        $path = $pathFinder->getPath($from, $to);
        $watchResult = $watch->stop('path');

        foreach ($path as $item) {
            $output->writeln(sprintf('%d:%d', $item->getX(), $item->getY()));
        }


        $output->writeln('Duration: '.$watchResult->getDuration());
        $output->writeln('Memory: '.$watchResult->getMemory() / (1024));
    }
}
