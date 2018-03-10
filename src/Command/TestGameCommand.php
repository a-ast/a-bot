<?php

namespace App\Command;

use App\Model\Game\Game;
use App\Model\Location\Location;
use App\Strategy\StrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestGameCommand extends Command
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    public function __construct(StrategyInterface $strategy)
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
        $mapData = file(__DIR__ . '/Map/m1.map', FILE_IGNORE_NEW_LINES);

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
                'crashed' => false,
                'pos' => ['x' => 3, 'y' => 1],
                'spawnPos' => ['x' => 0, 'y' => 1],
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

            $next = $this->strategy->getNextLocation();

            // @todo: update gold and life
            $game->getHero()->refresh(
                [
                    'pos' => ['x' => Location::getXY($next)[0], 'y' => Location::getXY($next)[1]],
                    'life' => 100,
                    'gold' => 0,
                    'crashed' => false,
                ]);


        } while (true);


    }
}
