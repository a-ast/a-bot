<?php

namespace App\Strategy\WeightedTactics;

use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\Location\LocationPrioritizer;
use App\Model\Location\LocationTrace;
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

    /**
     * @var LocationTrace
     */
    private $locationTrace;

    public function __construct(PathFinderInterface $pathFinder, LocationTrace $locationTrace, array $tactics)
    {
        $this->pathFinder = $pathFinder;
        $this->tactics = $tactics;
        $this->locationTrace = $locationTrace;
    }

    public function initialize(GamePlayInterface $game)
    {
        $this->game = $game;
        $this->hero = $game->getHero();

        $this->pathFinder->initialize($game->getMap(), $game->getTavernAndGoldMineLocations());
   }

    public function getNextLocation(): string
    {
        $possibleLocations = $this->getNearLocations();

        $weightPerLocation = [];
        $locationPrioritizer = new LocationPrioritizer();

        foreach ($possibleLocations as $nearLocation) {

            $weights = $this->getTotalLocationWeights($nearLocation);
            $weightPerLocation[$nearLocation] = $weights;

            $locationPrioritizer->add($nearLocation, array_sum($weights));
        }

        $selectedLocation = $locationPrioritizer->getWithMaxPriority()->getLocation();

        $this->locationTrace->add($selectedLocation);

        if (count($possibleLocations) > 1) {
            if ($this->locationTrace->isRepetitive(6, 2)) {
                $selectedLocation = $locationPrioritizer->getNextAfterMax()->getLocation();
            }

            if ($this->locationTrace->isRepetitive(9, 3)) {
                $selectedLocation = $locationPrioritizer->getNextAfterMax()->getLocation();
            }

            $this->locationTrace->replaceLast($selectedLocation);
        }

        $this->aggregateStats($weightPerLocation, $locationPrioritizer);

        return $selectedLocation;
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

    private function getLocationWeight(WeightedTacticInterface $tactic, string $location): int
    {
        // if a tactic is able to process this location
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

    public function getTacticStatistics(): array
    {
        return $this->analysis;
    }

    private function getNearLocations(): array
    {
        $heroLocation = $this->hero->getLocation();
        $nearLocations = $this->game->getMap()->getNearLocations($heroLocation);

        $possibleNearLocations = array_merge($nearLocations, [$heroLocation]);

        return $possibleNearLocations;
    }
}
