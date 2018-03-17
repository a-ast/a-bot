<?php


namespace App\Command;


use App\Game\GameBuilder;
use App\Game\TournamentGame;
use App\Model\Game\Board;
use App\Model\Game\Game;
use App\Model\Location\Location;
use App\Model\Location\LocationGraphInterface;
use App\Model\Location\LocationGraphBuilder;
use App\Model\Location\Road;
use App\Model\Location\Wall;
use App\PathFinder\AStarAlgorithm;
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

    /**
     * @var GameBuilder
     */
    private $gameBuilder;

    /**
     */
    public function __construct(GameBuilder $gameLoader)
    {
        parent::__construct();

        $this->gameBuilder = $gameLoader;
    }

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

        $game = new Game();
        $this->gameBuilder->buildObjects($game, $mapData);
        $gamePlay = $game->getGamePlay();

        $hero = $game->getHero();
        $hero->setLocation('0:6');
        $hero->setLifePoints(50);
        $hero->setGoldPoints(0);


        $pathFinder = new AStarAlgorithm();

        $watch = new Stopwatch(false);

        $watch->start('init path finder');
        $pathFinder->initialize($gamePlay->getMap(), $gamePlay->getTavernAndGoldMineLocations());
        $watchResult = $watch->stop('init path finder');


        $output->writeln('Duration: '.$watchResult->getDuration());
        $output->writeln('Memory: '.$watchResult->getMemory() / (1024));


        // and figure out why distances
        // 10:11-12:12 and 11:11-12:12 are both = 3

        print $pathFinder->getDistance('1:1', '1:3').PHP_EOL;


//        foreach ($gamePlay->getWalkableLocations() as $from) {
//
//            foreach ($gamePlay->getMap()->getLocations() as $to) {
//                $pathDistance = $pathFinder->getDistance($from, $to);
//
//                if ($from !== $to) {
//                    $next = $pathFinder->getNextLocation($from, $to);
//                }
//            }
//        }
    }
}
