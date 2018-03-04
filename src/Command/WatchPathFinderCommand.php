<?php


namespace App\Command;


use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\PathFinder\LeeAlgorythm;
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

        $from = $board->getTileAt(0, 0);
        $to = $board->getTileAt(8, 8);

        $gold1 = $board->getTileAt(15, 15);

        $pathFinder = new LeeAlgorythm();

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