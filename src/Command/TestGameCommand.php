<?php


namespace App\Command;


use App\Game\TrainingGame;
use App\Model\Game\Board;
use App\Model\Game\Game;
use App\Model\Game\TreasureBoard;
use App\Model\Tile\Road;
use App\PathFinder\LeeAlgorythm;
use App\Strategy\StatefulStrategy;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Stopwatch\Stopwatch;

class TestGameCommand extends Command
{

    /**
     * @var \App\Strategy\StatefulStrategy
     */
    private $strategy;

    public function __construct(StatefulStrategy $strategy)
    {
        parent::__construct();

        $this->strategy = $strategy;
    }

    protected function configure()
    {
        $this
            ->setName('a-bot:test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mapData = file(__DIR__ . '/Map/m6.map', FILE_IGNORE_NEW_LINES);

        $maxWidth = max(array_map('strlen', $mapData));
        $mapData = array_map(function($item) use ($maxWidth) { return str_pad($item, $maxWidth); }, $mapData);

        $boardWidth = $maxWidth / 2;
        $tileData = join('', $mapData);

        $state = [
            'playUrl' => '',
            'viewUrl' => '',

            'hero' => [
                'name' => 'Frodo',
                'id' => 1,
                'life' => 100,
                'gold' => 0,
                'pos' => ['x' => 0, 'y' => 0],
            ],
            'game' => [
                'finished' => false,
                'heroes' => [],
                'board' => [
                    'size' => $boardWidth,
                    'tiles' => $tileData,
                ],
            ],
        ];

        $game = new Game($state);
        $this->strategy->initialize($game);

        do {

            $next = $this->strategy->getNextTile();

            // @todo: update gold and life

            if ($next instanceof Road) {
                $game->getHero()->refresh(
                    [
                        'pos' => ['x' => $next->getX(), 'y' => $next->getY()],
                        'life' => 100,
                        'gold' => 0,
                    ]);
            }

        } while (true);


    }
}