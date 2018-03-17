<?php

namespace App\Strategy;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\PathFinder\LeeAlgorithm;
use SplObjectStorage;

class StatefulStrategy implements StrategyInterface
{
    private $state;

    /**
     * @var LeeAlgorithm
     */
    private $pathFinder;


    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var string
     */
    private $goalTile;

    /**
     * @var array
     */
    private $goalPath;

    /**
     * @var GameInterface
     */
    private $game;


    public function __construct(LeeAlgorithm $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function initialize(GamePlayInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();
        $this->board = $game->getBoard();
        $goals = array_merge($this->board->getGoldMines(), $this->board->getTaverns());
        $this->pathFinder->initialize($this->board, $goals);

        $this->state = 'Init';
    }

    public function getNextLocation(): string
    {
        print $this->state . PHP_EOL;

        if ($this->hero->isRespawned()) {
            print '****** Heilige Scheisse *******'. PHP_EOL;
            $this->state = 'Init';
        }

        // if enemy near, fight
//        $enemy = $this->game->getNearHero($this->hero);
//
//        if ($enemy instanceof Enemy) {
//            print '****** BANZAI *******'. PHP_EOL;
//
//            return $enemy;
//        }

        foreach ($this->game->getRivalHeroes() as $enemy) {
            if ($this->hero->isNear($enemy)) {
                print '****** BANZAI *******'. PHP_EOL;

                return $enemy;
            }
        }

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

                    $potentialGoals = $this->board->getForeignGoldMines();

                    // if all gold is mine, no hurry
                    if (0 === count($potentialGoals)) {
                        return $this->hero;
                    }

                    $this->findGoal($potentialGoals, 'GoToGold');

                    break;

                case 'FindTavern':

                    $this->findGoal($this->board->getTaverns(), 'GoToTavern');

                    break;

                case 'GoToGold':

                    if ($this->hero->getLifePoints() < 30) {
                        $this->state = 'FindTavern';

                        break;
                    }

                    return $this->goToGoal();



                case 'GoToTavern':

                    // go to tavern

                    // if found, FindGold

                    return $this->goToGoal();

                case 'Drink':
                    if ($this->hero->getLifePoints() < 90) {

                        // Noch ein Bier bitte

                        return $this->goToGoal();
                    }

                    $this->state = 'FindGold';


            }

        } while (true);
    }

    private function goToGoal()
    {
        if ($this->hero->isNear($this->goalTile)) {

            if ('GoToTavern' === $this->state) {
                $this->state = 'Drink';
            } else {

                $this->state = 'FindGold';
            }


            return $this->goalTile;
        }

        // find next node
        for ($i = 0; $i < count($this->goalPath); $i++) {
            if ($this->hero->isOn($this->goalPath[$i])) {

                return $this->goalPath[$i+1];

            }
        }

        return $this->goalPath[0];
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

        ksort($pathLengths);
        reset($pathLengths);

        $this->goalTile = current($pathLengths);
        $this->goalPath = $paths[$this->goalTile];

        $this->state = $finalState;
    }

    public function getCurrentAnalysis(): array
    {
        return [];
    }
}
