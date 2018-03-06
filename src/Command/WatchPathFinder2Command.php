<?php


namespace App\Command;


use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\Model\Location\LocationMatrixInterface;
use App\Model\Location\LocationMatrixBuilder;
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

class WatchPathFinder2Command extends Command
{

    protected function configure()
    {
        $this
            ->setName('a-bot:path2')
            ->addArgument('map-name', InputArgument::REQUIRED)


        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = sprintf('/Map/%s.map', $input->getArgument('map-name'));
        $mapData = file_get_contents(__DIR__ .$fileName, FILE_IGNORE_NEW_LINES);

        $builder = new LocationMatrixBuilder();
        $matrix = $builder->buildFromTextWithEol($mapData);

//        $from = $matrix->getLocation(0, 0);
//        $to = $matrix->getLocation(8, 8);

        $pathFinder = new FloydWarshallAlgorithm();

        $watch = new Stopwatch(false);

        $watch->start('init path finder');
        $pathFinder->initialize($matrix);
        $watchResult = $watch->stop('init path finder');

        //$pathDistance = $pathFinder->getDistance($from, $to);
        //$next = $pathFinder->getNextLocation($from, $to);



//
//
//        foreach ($path as $item) {
//            $output->writeln(sprintf('%d:%d', $item->getX(), $item->getY()));
//        }

        $output->writeln('Duration: '.$watchResult->getDuration());
        $output->writeln('Memory: '.$watchResult->getMemory() / (1024));
    }
}
