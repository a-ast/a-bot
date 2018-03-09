<?php

namespace App\Strategy;

use App\Model\BoardInterface;
use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\Location\Location;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;
use App\Model\LocationInterface;
use App\PathFinder\FloydWarshallAlgorithm;
use SplObjectStorage;

class TacticSetStrategy implements StrategyInterface
{
    /**
     * @var \App\PathFinder\FloydWarshallAlgorithm
     */
    private $pathFinder;

    /**
     * @var BoardInterface
     */
    private $board;

    /**
     * @var HeroInterface
     */
    private $hero;

    /**
     * @var GameInterface
     */
    private $game;

    /**
     * @var int[]
     */
    private $friendIds;


    public function __construct(FloydWarshallAlgorithm $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    public function initialize(GameInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();
        $this->board = $game->getBoard();

        $this->friendIds = $game->getFriendIds();

        $goldMines = $this->board->getGoldMines();
        $taverns = $this->board->getTaverns();
        $goals = $goldMines->addMap($taverns);

        $this->pathFinder->initialize($this->board->getMap(), $goals);
    }

    public function getNextLocation(): LocationInterface
    {

        $enemyNear = $this->getClosestEnemy();
        $tavernNear = $this->getClosestTavern();
        $goldNear = $this->getClosestGoldMine();

        // Attack
        if ($enemyNear instanceof HeroInterface &&
            $enemyNear->getLifePoints() <= $this->hero->getLifePoints()) {

            print '## ATTACK'.PHP_EOL;

            return $enemyNear;

        }

        // Avoid attack
        if ($enemyNear instanceof HeroInterface &&
            $enemyNear->getLifePoints() > $this->hero->getLifePoints()) {

            print '#####################'.PHP_EOL;
            print '## RUN FORREST RUN ##'.PHP_EOL;
            print '#####################'.PHP_EOL;

            // Get 4 tiles near
            $nearLocations = $this->board->getMap()->getNearLocations($this->hero->getLocation());

            /** @var LocationInterface[] $potentialLocationsToGo */
            $potentialLocationsToGo = [];
            foreach ($nearLocations as $nearLocation) {

                if ($nearLocation !== $enemyNear->getLocation()) {
                    $potentialLocationsToGo[] = $nearLocation;
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
                $distanceFromEnemy = $this->pathFinder->getDistance($potentialLocation, $enemyNear->getLocation());

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

            return $this->findGoal($this->board->getTaverns());
        }

        // If near gold, take it (hero loses 20 life points)
        if ($goldNear instanceof GoldMine && $this->hero->getLifePoints() > 20) {

            print '## TAKE GOLD NEAR'.PHP_EOL;

            return $goldNear->getLocation();
        }

        // Go to gold

        if ($this->hero->getLifePoints() > 20) {
            $goldMines = $this->board->getForeignGoldMines($this->friendIds);

            if (count($goldMines) > 0) {

                print '## GO GOLD'.PHP_EOL;

                $next = $this->findGoal($goldMines);

                return $next;

            }

        }

        // Otherwise stay
        print '## STAY HERE'.PHP_EOL;
        return $this->hero->getLocation();

    }

    private function getClosestEnemy()
    {
        foreach ($this->game->getHeroes() as $enemy) {
            if ($this->hero->getLocation()->isNear($enemy->getLocation())) {
                return $enemy;
            }
        }

        return new Location(-1, -1);
    }

    private function getClosestGoldMine()
    {
        $goldMines = $this->board->getForeignGoldMines($this->friendIds);

        foreach ($goldMines as $goldMine) {
            if ($this->hero->getLocation()->isNear($goldMine->getLocation())) {
                return $goldMine;
            }
        }

        return new Location(-1, -1);
    }

    private function getClosestTavern()
    {
        foreach ($this->game->getBoard()->getTaverns() as $tavern) {
            if ($this->hero->getLocation()->isNear($tavern->getLocation())) {
                return $tavern;
            }
        }

        return new Location(-1, -1);
    }


    private function findGoal(array $potentialGoals): LocationInterface
    {
        $paths = new SplObjectStorage();

        foreach ($potentialGoals as $item) {


            // add goal to the path
            $path[] = $item;

            $paths[$item] = $path;
        }

        $minPathLength = 10000;
        $goal = null;
        $nextLocation = null;

        foreach ($potentialGoals as $pathGoal) {

            $pathDistance = $this->pathFinder->getDistance($this->hero->getLocation(), $item->getLocation());
            $pathNextLocation = $this->pathFinder->getNextLocation($this->hero->getLocation(), $item->getLocation());


            if ($pathDistance < $minPathLength) {

                $minPathLength = $pathDistance;
                $nextLocation = $pathNextLocation;
                $goal = $pathGoal;
            }
        }


        print '  @@@@ Selected goal:' . $goal . PHP_EOL;

        return $nextLocation;
    }
}
