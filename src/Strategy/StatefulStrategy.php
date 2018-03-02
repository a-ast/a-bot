<?php

namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\TileInterface;
use App\Model\TreasureBoardInterface;
use App\PathFinder\LeeAlgorythm;
use SplObjectStorage;

class StatefulStrategy implements StrategyInterface
{
    private $state;

    /**
     * @var LeeAlgorythm
     */
    private $pathFinder;

    /**
     * @var TreasureBoardInterface
     */
    private $board;

    /**
     * @var HeroInterface
     */
    private $hero;

    /**
     * @var TileInterface
     */
    private $goalTile;

    /**
     * @var array
     */
    private $goalPath;


    public function __construct(LeeAlgorythm $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function initialize(GameInterface $game)
    {
        $this->hero = $game->getHero();
        $this->board = $game->getBoard();
        $goals = array_merge($this->board->getGoldMines(), $this->board->getTaverns());
        $this->pathFinder->initialize($this->board, $goals);

        $this->state = 'Init';
    }

    public function getNextTile(): TileInterface
    {
        print $this->state . PHP_EOL;

        do {

            switch ($this->state) {

                case 'Init':

                    $this->state = 'FindGold';

                    break;

                case 'FindGold':

                    if ($this->hero->getLifePoints() < 30) {
                        $this->state = 'FindTavern';

                        break;
                    }

                    $this->findGoal($this->board->getGoldMines($this->hero), 'GoToGold');

                    break;

                case 'FindTavern':

                    $this->findGoal($this->board->getTaverns(), 'GoToTavern');

                    break;

                case 'GoToGold':

                    if ($this->hero->getLifePoints() < 30) {
                        $this->state = 'GoToTavern';

                        break;
                    }

                    // Check if enough HP

                    // if not change state to GoToTavern

                    return $this->goToGoal();



                case 'GoToTavern':

                    // go to tavern

                    // if found, FindGold

                    return $this->goToGoal();



            }
        } while (true);
    }

    private function goToGoal()
    {
        if ($this->hero->isNear($this->goalTile)) {

            $this->state = 'FindGold';

            return $this->goalTile;
        }

        // Just use next()
        $nextTile = current($this->goalPath);
        next($this->goalPath);

        return $nextTile;
    }


    private function findGoal(array $potentialGoals, string $finalState)
    {
        $paths = new SplObjectStorage();
        $pathLengths = [];

        foreach ($potentialGoals as $item) {

            $path = $this->pathFinder->getPath($this->hero, $item);
            $paths[$item] = $path;
            $pathLengths[count($path)] = $item;
        }

//        if (0 === count($paths)) {
//
//            // Really? @todo: better fight
//            $this->state = 'FindTavern';
//
//            break;
//        }

        ksort($pathLengths);
        reset($pathLengths);

        $this->goalTile = current($pathLengths);
        $this->goalPath = $paths[$this->goalTile];

        $this->state = $finalState;
    }
}