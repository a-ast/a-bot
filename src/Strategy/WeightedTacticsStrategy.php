<?php

namespace App\Strategy;

use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\Location\LocationPrioritizer;
use App\PathFinder\PathFinderInterface;

class WeightedTacticsStrategy implements StrategyInterface
{
    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var GamePlayInterface
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

    /**
     * @var array
     */
    private $analysis;

    public function __construct(PathFinderInterface $pathFinder, array $tactics)
    {
        $this->pathFinder = $pathFinder;
        $this->tactics = $tactics;
    }

    public function initialize(GamePlayInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();

        $this->pathFinder->initialize($game->getMap(), $game->getTavernAndGoldMineLocations());
   }

    public function getNextLocation(): string
    {
        $heroLocation = $this->hero->getLocation();
        $nearLocations = $this->game->getMap()->getNearLocations($heroLocation);

        $possibleNearLocations = array_merge($nearLocations, [$heroLocation]);

        $weightDebugger = [];
        $locationPrioritizer = new LocationPrioritizer();

        foreach ($possibleNearLocations as $nearLocation) {

            $weights = $this->getLocationWeights($nearLocation);
            $weightDebugger[$nearLocation] = $weights;

            $locationPrioritizer->add($nearLocation, array_sum($weights));
        }

        $selectedLocation = $locationPrioritizer->getWithMaxPriority()->getLocation();
        $nextLocation = $this->pathFinder->getNextLocation($heroLocation, $selectedLocation);

        $this->analysis = [
            'weights' => $weightDebugger,
            'locations' => $locationPrioritizer->toArray(),
        ];

        return $nextLocation;
    }

    /**
     * @return int[]
     */
    private function getLocationWeights(string $location): array
    {
        $weights = [];

        $coefficients = [
            'take near gold' => 1020,
            'find gold' => 1010,

            'take near beer' => 1000,
            'find tavern' => 990,

            'attack hero' => 980,
            'find hero' => 970,
            'avoid hero' => 965,
        ];

        foreach ($this->tactics as $tacticName => $tactic) {

            $applicableLocation = $location;

            if (false === $tactic->isApplicableLocation($this->game, $location)) {
                $applicableLocation = $this->hero->getLocation();
            }

            $weights[$tacticName] =
                $coefficients[$tacticName] *
                $tactic->getWeight($this->game, $applicableLocation);
        }

        return $weights;
    }

    public function getCurrentAnalysis(): array
    {
        return $this->analysis;
    }
}
