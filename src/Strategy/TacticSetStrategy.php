<?php

namespace App\Strategy;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\LocationAwareListInterface;
use App\PathFinder\FloydWarshallAlgorithm;

class TacticSetStrategy implements StrategyInterface
{
    /**
     * @var \App\PathFinder\FloydWarshallAlgorithm
     */
    private $pathFinder;

    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var GamePlayInterface
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

    public function initialize(GamePlayInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();

        $this->pathFinder->initialize($game->getMap(), $game->getTavernAndGoldMineLocations());
    }

    public function getNextLocation(): string
    {
        $enemyNear = $this->getClosestEnemy();
        $tavernNear = $this->getClosestTavern();
        $goldNear = $this->getClosestGoldMine();

        // Attack
        if ($enemyNear instanceof Hero &&
            $enemyNear->getLifePoints() <= $this->hero->getLifePoints()) {

            print '## ATTACK'.PHP_EOL;

            return $enemyNear->getLocation();

        }

        // Avoid attack
        if ($enemyNear instanceof Hero &&
            $enemyNear->getLifePoints() > $this->hero->getLifePoints()) {

            print '#####################'.PHP_EOL;
            print '## RUN FORREST RUN ##'.PHP_EOL;
            print '#####################'.PHP_EOL;

            // Get 4 tiles near
            $nearLocations = $this->game->getMap()->getNearLocations($this->hero->getLocation());
            $nearLocations = array_diff($nearLocations, $this->game->getTavernAndGoldMineLocations());

            /** @var string[] $potentialLocationsToGo */
            $potentialLocationsToGo = [];
            foreach ($nearLocations as $nearLocation) {

                if ($nearLocation !== $enemyNear->getLocation()) {
                    $potentialLocationsToGo[] = $nearLocation;
                }
            }

            // no way to run
            if (0 === count($potentialLocationsToGo)) {
                return $enemyNear->getLocation();
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

            return $tavernNear->getLocation();
        }


        // Go to tavern
        if ($this->hero->getLifePoints() < 30) {

            print '## GO TAVERN'.PHP_EOL;

            return $this->findGoal($this->game->getTaverns());
        }

        // If near gold, take it (hero loses 20 life points)
        if ($goldNear instanceof GoldMine && $this->hero->getLifePoints() > 20) {

            print '## TAKE GOLD NEAR'.PHP_EOL;

            return $goldNear->getLocation();
        }

        // Go to gold

        if ($this->hero->getLifePoints() > 20) {

            $goldMines = $this->game->getForeignGoldMines();

            if ($goldMines->count() > 0) {

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
        foreach ($this->game->getRivalHeroes() as $enemy) {
            if (1 === $this->pathFinder->getDistance($this->hero->getLocation(), $enemy->getLocation())) {
                return $enemy;
            }
        }

        return null;
    }

    private function getClosestGoldMine()
    {
        $goldMines = $this->game->getForeignGoldMines();

        foreach ($goldMines as $goldMine) {
            if (1 === $this->pathFinder->getDistance($this->hero->getLocation(), $goldMine->getLocation())) {
                return $goldMine;
            }
        }

        return null;
    }

    private function getClosestTavern()
    {
        foreach ($this->game->getTaverns() as $tavern) {
            if (1 === $this->pathFinder->getDistance($this->hero->getLocation(), $tavern->getLocation())) {
                return $tavern;
            }
        }

        return null;
    }


    private function findGoal(LocationAwareListInterface $potentialGoals): string
    {
        $minPathLength = 10000;
        $goal = null;
        $nextLocation = null;

        foreach ($potentialGoals->getLocations() as $pathGoal) {

            $pathDistance = $this->pathFinder->getDistance($this->hero->getLocation(), $pathGoal);
            $pathNextLocation = $this->pathFinder->getNextLocation($this->hero->getLocation(), $pathGoal);


            if ($pathDistance < $minPathLength) {

                $minPathLength = $pathDistance;
                $nextLocation = $pathNextLocation;
                $goal = $pathGoal;
            }
        }


        print '  @@@@ Selected goal:' . $goal . PHP_EOL;

        return $nextLocation;
    }

    public function getCurrentAnalysis(): array
    {
        return [];
    }
}
