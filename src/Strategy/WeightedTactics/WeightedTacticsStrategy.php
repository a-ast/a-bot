<?php

namespace App\Strategy\WeightedTactics;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\Location\LocationPrioritizer;
use App\PathFinder\PathFinderInterface;
use App\Strategy\StrategyInterface;

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
     * @var WeightedTacticInterface[]
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

        $weightPerLocation = [];
        $locationPrioritizer = new LocationPrioritizer();

        foreach ($possibleNearLocations as $nearLocation) {

            $weights = $this->getTotalLocationWeights($nearLocation);
            $weightPerLocation[$nearLocation] = $weights;

            $locationPrioritizer->add($nearLocation, array_sum($weights));
        }

        $selectedLocation = $locationPrioritizer->getWithMaxPriority()->getLocation();
        $nextLocation = $this->pathFinder->getNextLocation($heroLocation, $selectedLocation);

        $this->aggregateStats($weightPerLocation, $locationPrioritizer);

        return $nextLocation;
    }

    /**
     * @return int[]
     */
    private function getTotalLocationWeights(string $location): array
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

            $weight = $this->getLocationWeight($tactic, $location);

            $weights[$tacticName] = $coefficients[$tacticName] * $weight;
        }

        return $weights;
    }

    public function getCurrentAnalysis(): array
    {
        return $this->analysis;
    }

    private function getLocationWeight(WeightedTacticInterface $tactic, string $location): int
    {
        // if a tactic can process this location
        if ($tactic->isApplicableLocation($this->game, $location)) {
            return $tactic->getWeight($this->game, $location, false);
        }

        $heroLocation = $this->hero->getLocation();

        // if not, try to process a hero location
        if ($tactic->isApplicableLocation($this->game, $heroLocation)) {
            return $tactic->getWeight($this->game, $heroLocation, true);
        }

        // otherwise this tactic don't work in this case
        return 0;
    }

    private function aggregateStats(array $weightPerLocation, LocationPrioritizer $locationPrioritizer)
    {
        $this->analysis = [
            'weights' => $weightPerLocation,
            'locations' => $locationPrioritizer->toArray(),
        ];
    }
}
