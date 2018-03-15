<?php

namespace App\Command;

use App\Game\GameBuilder;
use App\Model\Game\Game;
use App\Model\Game\GoldMine;
use App\Model\Game\Hero;
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

    /**
     * @var \App\Game\GameBuilder
     */
    private $gameBuilder;

    public function __construct(GameBuilder $gameBuilder, StrategyInterface $strategy)
    {
        parent::__construct();

        $this->gameBuilder = $gameBuilder;
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
        $mapData = file(__DIR__ . '/Map/a12.map', FILE_IGNORE_NEW_LINES);

        $maxWidth = max(array_map('strlen', $mapData));
        $mapData = array_map(function($item) use ($maxWidth) { return str_pad($item, $maxWidth); }, $mapData);

        $game = new Game();
        $this->gameBuilder->buildObjects($game, $mapData);

        $this->strategy->initialize($game->getGamePlay());

        $hero = $game->getHero();
        $hero->setLocation('1:7');
        $hero->setLifePoints(1);
        $hero->setGoldPoints(130);

        $rival = new Hero(2, 'Ayvengo', '6:0', '0:0');
        $rival->setLifePoints(80);
        $rival->setGoldPoints(10);
        $game->addRivalHero($rival);

        $game->getGoldMines()->get('3:9')->setHeroId(1);
        $game->getGoldMines()->get('3:10')->setHeroId(1);


        $turns = 10;
        $currentTurn = 0;


        do {
            print 'Hero: '. $game->getHero()->getLocation().PHP_EOL;

            $next = $this->strategy->getNextLocation();

            if ($game->getGamePlay()->isGameObjectAt($next)) {
                $next = $game->getHero()->getLocation();
            }

            $hero->setLocation($next);

            print $game->getHero()->getLocation().' -> '.$next.PHP_EOL;

            $currentTurn++;

        } while ($currentTurn <= $turns);
    }
}
