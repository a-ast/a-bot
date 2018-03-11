<?php

namespace App\Strategy;

use App\Model\BoardInterface;
use App\Model\Game\LocationAwareListInterface;
use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\Location\LocationPrioritizer;
use App\Model\Location\LocationPriorityPair;
use App\PathFinder\FloydWarshallAlgorithm;
use App\PathFinder\PathFinderInterface;

class WeighedTacticsStrategy implements StrategyInterface
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

    public function __construct(FloydWarshallAlgorithm $pathFinder)
    {
        $this->pathFinder = $pathFinder;
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

        foreach ($nearLocations as $nearLocation) {

            // if location is goldmine/tavern/hero, calc from hero point
            // because hero will not move
            $stayLocation = $this->isGameObject($nearLocation) ?
                $this->hero->getLocation() :
                $nearLocation;

            $priority = $this->getLocationPriority($stayLocation);
            $locationPrioritizer->add($nearLocation, $priority);
        }

        $locationPrioritizer->dump('Select location');
        $selectedLocation = $locationPrioritizer->getWithMaxPriority()->getLocation();

        $nextLocation = $this->pathFinder->getNextLocation($heroLocation, $selectedLocation);

        return $nextLocation;
    }

    private function getLocationPriority(string $location): int
    {
        return
            1000 * $this->takeGoldTacticPrio($location)
            + 1000 * $this->takeBearTacticPrio($location)
            + 1000 * $this->attackWeakHeroTacticPrio($location)
            ;
    }

    private function getClosestLocationWithDistance(string $location,
        LocationAwareListInterface $goals): LocationPriorityPair
    {
        if (0 === count($goals)) {
            return new LocationPriorityPair($location, 0);
        }

        $prioritizer = new LocationPrioritizer();

        foreach ($goals as $item) {

            $distance = $this->pathFinder->getDistance(
                $location, $item->getLocation());
            $prioritizer->add($item->getLocation(), $distance);
        }

        //$prioritizer->dump('Bear finder');



        $pair = $prioritizer->getWithMinPriority();

        return $pair;
    }

    private function takeGoldTacticPrio(string $location): int
    {
        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $this->board->getForeignGoldMines($this->game->getFriendIds())
        );

        $distance = $locationWithDistance->getPriority();
        if ($this->hero->getLifePoints() - $distance < 25) {
            return 0;
        }

        return 1000 - 10 * $locationWithDistance->getPriority();
    }

    private function takeBearTacticPrio(string $location): int
    {
        $k = $this->hero->getLifePoints() < 25 ? 10 : 1;

        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $this->board->getTaverns()
        );

        return 1000 - $k * $locationWithDistance->getPriority();
    }

    /**
     * @param $nearLocation
     *
     * @return bool
     */
    private function isGameObject($nearLocation): bool
    {
        return in_array($nearLocation, $this->board->getGoalLocations());
    }

    private function attackWeakHeroTacticPrio($location)
    {
        $locationWithDistance = $this->getClosestLocationWithDistance(
            $location,
            $this->game->getHeroes()
        );

        $distance = $locationWithDistance->getPriority();

        /** @var HeroInterface $hero */
        $hero = $this->game->getHeroes()->get($locationWithDistance->getLocation());

        if ($distance < 3 &&
            $hero->getLifePoints() < $this->hero->getLifePoints() &&
            count($this->board->getGoldMinesOf($hero->getId())) > 0

        ) {

            var_dump($hero);

            return 1000 - 10 * $locationWithDistance->getPriority();
        }

        return 0;
    }
}
