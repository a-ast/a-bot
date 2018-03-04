<?php

namespace App\Strategy;

use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\Tile\Enemy;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Hero;
use App\Model\Tile\NoTile;
use App\Model\Tile\Tavern;
use App\Model\TileInterface;
use App\Model\TreasureBoardInterface;
use App\PathFinder\LeeAlgorythm;
use SplObjectStorage;

class TacticSetStrategy implements StrategyInterface
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

    /**
     * @var GameInterface
     */
    private $game;


    public function __construct(LeeAlgorythm $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function initialize(GameInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();
        $this->board = $game->getBoard();
        $goals = array_merge($this->board->getGoldMines(), $this->board->getTaverns());

        $this->pathFinder->initialize($this->board, $goals);
    }

    public function getNextTile(): TileInterface
    {

        $enemyNear = $this->getClosestEnemy();
        $tavernNear = $this->getClosestTavern();
        $goldNear = $this->getClosestGoldMine();

        // Attack
        if ($enemyNear instanceof Enemy &&
            $enemyNear->getLifePoints() <= $this->hero->getLifePoints()) {

            print '## ATTACK'.PHP_EOL;

            return $enemyNear;

        }

        // Avoid attack
        if ($enemyNear instanceof Enemy &&
            $enemyNear->getLifePoints() > $this->hero->getLifePoints()) {

            print '#####################'.PHP_EOL;
            print '## RUN FORREST RUN ##'.PHP_EOL;
            print '#####################'.PHP_EOL;

            // Get 4 tiles near
            $nearTiles = $this->board->getNearTiles($this->hero, true);

            /** @var TileInterface[] $potentialLocationsToGo */
            $potentialLocationsToGo = [];
            foreach ($nearTiles as $nearTile) {

                $enemyHero = $this->game->getHeroOn($nearTile);

                if (!($enemyHero instanceof Hero)) {
                    $potentialLocationsToGo[] = $nearTile;
                }
            }

            // no way to run
            if (0 === count($potentialLocationsToGo)) {
                return $enemyNear;
            }

            // choose between possible location one that far from enemies
            $maxDistanceToEnemy = 0;
            $locationToGo = null;

            foreach ($potentialLocationsToGo as $potentialLocation) {
                $distanceFromEnemy = $potentialLocation->getDirectDistanceTo($enemyNear);

                if ($distanceFromEnemy >= $maxDistanceToEnemy) {
                    $maxDistanceToEnemy = $distanceFromEnemy;

                    $locationToGo = $potentialLocation;
                }
            }

            return $locationToGo;
        }


        // If near tavern, drink more
        if ($tavernNear instanceof Tavern &&
            $this->hero->getLifePoints() < 90) {

            print '## DRINK MORE'.PHP_EOL;

            return $tavernNear;
        }


        // Go to tavern
        if ($this->hero->getLifePoints() < 30) {

            print '## GO TAVERN'.PHP_EOL;

            $path = $this->findGoal($this->board->getTaverns());

            return $path[0];
        }

        // If near gold, take it (hero loses 20 life points)
        if ($goldNear instanceof GoldMine && $this->hero->getLifePoints() > 20) {

            print '## TAKE GOLD NEAR'.PHP_EOL;

            return $goldNear;
        }

        // Go to gold

        if ($this->hero->getLifePoints() > 20) {
            $goldMines = $this->board->getGoldMines($this->hero);

            if (count($goldMines) > 0) {

                print '## GO GOLD'.PHP_EOL;

                $path = $this->findGoal($goldMines);

                return $path[0];

            }

        }



        // Otherwise stay
        print '## STAY HERE'.PHP_EOL;
        return $this->hero;

    }

    private function getClosestEnemy()
    {
        foreach ($this->game->getHeroes() as $enemy) {
            if ($this->hero->isNear($enemy)) {
                return $enemy;
            }
        }

        return new NoTile(-1, -1);
    }

    private function getClosestGoldMine()
    {
        $goldMines = $this->board->getGoldMines($this->hero);

        foreach ($goldMines as $goldMine) {
            if ($this->hero->isNear($goldMine)) {
                return $goldMine;
            }
        }

        return new NoTile(-1, -1);
    }

    private function getClosestTavern()
    {
        foreach ($this->game->getBoard()->getTaverns() as $tavern) {
            if ($this->hero->isNear($tavern)) {
                return $tavern;
            }
        }

        return new NoTile(-1, -1);
    }


    private function findGoal(array $potentialGoals): array
    {
        $paths = new SplObjectStorage();

        foreach ($potentialGoals as $item) {

            $path = $this->pathFinder->getPath($this->hero, $item);

            // add goal to the path
            $path[] = $item;

            $paths[$item] = $path;
        }

        $minPathLength = 10000;
        $minPath = null;
        $goal = null;

        foreach ($potentialGoals as $pathGoal) {

            $path = $paths[$pathGoal];

            if (count($path) < $minPathLength) {

                $minPathLength = count($path);
                $minPath = $path;
                $goal = $pathGoal;
            }
        }


        print '  @@@@ Selected goal:' . $goal . PHP_EOL;

        return $minPath;
    }
}