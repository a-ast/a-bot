<?php

namespace App\Strategy;

use App\Model\BoardInterface;
use App\Model\Game\GoldMine;
use App\Model\Game\LocationAwareListInterface;
use App\Model\Game\Tavern;
use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\Location\LocationPrioritizer;
use App\Model\Location\LocationPriorityPair;
use App\PathFinder\FloydWarshallAlgorithm;
use App\PathFinder\PathFinderInterface;

class WeightedTacticsStrategy implements StrategyInterface
{
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
     * @var PathFinderInterface
     */
    private $pathFinder;

    /**
     * @var WeightedTactic\WeightedTacticInterface[]
     */
    private $tactics;

    public function __construct(PathFinderInterface $pathFinder, array $tactics)
    {
        $this->pathFinder = $pathFinder;
        $this->tactics = $tactics;
    }

    public function initialize(GameInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();
        $this->board = $game->getBoard();

        $this->pathFinder->initialize($this->board->getMap(), $this->board->getGoalLocations());
   }

    public function getNextLocation(): string
    {
        $locationPrioritizer = new LocationPrioritizer();

        $heroLocation = $this->hero->getLocation();
        $nearLocations =
            $this->board->getMap()->getNearLocations($heroLocation) +
            [$this->hero->getLocation()];

        $weightDebugger = [];

        foreach ($nearLocations as $nearLocation) {

            $weights = $this->getLocationWeights($nearLocation);

            $weightDebugger[$nearLocation] = $weights;

            $locationPrioritizer->add($nearLocation, array_sum($weights));
        }

        $this->dumpWeights($weightDebugger);

        $locationPrioritizer->dump('Select location');
        $selectedLocation = $locationPrioritizer->getWithMaxPriority()->getLocation();

        $nextLocation = $this->pathFinder->getNextLocation($heroLocation, $selectedLocation);

        return $nextLocation;
    }

    /**
     * @return int[]
     */
    private function getLocationWeights(string $location): array
    {
        $weights = [];

        $coefficients = [
            'take gold' => 1001,
            'take beer' => 1000,
        ];

        foreach ($this->tactics as $tacticName => $tactic) {
            $weights[$tacticName] =
                $coefficients[$tacticName] *
                $tactic->getWeight($this->game, $location);
        }

        return $weights;
    }



    private function attackWeakHeroTacticPrio($location)
    {
        if (0 === $this->game->getHeroes()->count()) {
            return 0;
        }

        if ($this->board->isGoal($location)) {
            $goal = $this->board->getGoal($location);

            if ($goal instanceof GoldMine ||
                $goal instanceof Tavern
            ) {
                return 0;
            }
        }


        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $this->game->getHeroes()
        );

        $distance = $locationWithDistance->getPriority();

        /** @var HeroInterface $hero */
        $closestLocation = $locationWithDistance->getLocation();


        $hero = $this->game->getHeroes()->get($closestLocation);

        if ($distance < 3 &&
            $hero->getLifePoints() < $this->hero->getLifePoints() &&
            count($this->board->getGoldMinesOf($hero->getId())) > 0

            // do not attack heroes that stay on their spawn ?
            && (!$hero->isOnSpawnLocation() && $distance === 1)

        ) {

            return 1000 - 10 * $locationWithDistance->getPriority();
        }

        return 0;
    }

    private function avoidStrongHeroTacticPrio($location)
    {
        if (0 === $this->game->getHeroes()->count()) {
            return 0;
        }

        if ($this->board->isGoal($location)) {
            $goal = $this->board->getGoal($location);

            if ($goal instanceof GoldMine ||
                $goal instanceof Tavern
            ) {
                return 0;
            }
        }


        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $this->game->getHeroes()
        );

        $distance = $locationWithDistance->getPriority();

        $closestLocation = $locationWithDistance->getLocation();

        /** @var HeroInterface $hero */
        $hero = $this->game->getHeroes()->get($closestLocation);

        if ($distance < 3 &&
            $hero->getLifePoints() > $this->hero->getLifePoints()

        ) {
            return -1000;
        }

        return 0;
    }

    public function getAlias(): string
    {
        return 'w';
    }

    private function dumpWeights(array $weights)
    {
        $text = PHP_EOL;

        foreach ($weights as $location => $tactics) {
            $text .= $location .PHP_EOL;
            foreach ($tactics as $tacticName => $weight) {
                $text .= sprintf('    %s - %d', $tacticName, $weight).PHP_EOL;
            }
        }

        print $text;
    }
}
