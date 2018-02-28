<?php


namespace App\Command;


use App\Game\TrainingGame;
use App\Model\Game\Board;
use App\PathFinder\LeeAlgorythm;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        $mapData = file(__DIR__ . '/Map/long-wall.map', FILE_IGNORE_NEW_LINES);

        $board = new Board(strlen($mapData[0]) / 2, join('', $mapData));

        $from = $board->getTileAt(4, 3);
        $to = $board->getTileAt(1, 1);

        $pathFinder = new LeeAlgorythm();
        $pathFinder->getPath($board, $from, $to);
    }
}