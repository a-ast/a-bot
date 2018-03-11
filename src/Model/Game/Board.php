<?php

namespace App\Model\Game;

use App\Model\BoardInterface;
use App\Model\Location\Location;
use App\Model\Location\LocationGraph;
use App\Model\LocationGraphInterface;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;

class Board implements BoardInterface
{
    /**
     * @var LocationGraphInterface
     */
    protected $map;

    /**
     * @var LocationAwareListInterface|GoldMine[]
     */
    private $goldMines;

    /**
     * @var LocationAwareListInterface|Tavern[]
     */
    private $taverns;

    /**
     * @var int
     */
    private $boardSize;

    /**
     * @var array
     */
    private $goalLocations = [];

    public function __construct(int $boardWidth, string $tileData)
    {
        $this->boardSize = $boardWidth;

        $this->map = new LocationGraph();
        $this->goldMines = new LocationAwareList();
        $this->taverns = new LocationAwareList();

        $this->loadInitialTiles($tileData);
    }

    public function getWidth(): int
    {
        return $this->boardSize;
    }

    private function loadInitialTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->boardSize);

        print join(PHP_EOL, $mapLines).PHP_EOL;

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('##' === $item) {
                    continue;
                }

                // everything belongs to map except of wood
                $location = $this->map->add($x, $y);

                if ('$' === $item[0]) {
                    $this->goldMines->add(new GoldMine($location));
                    $this->goalLocations[] = $location;
                }

                if ('[]' === $item) {
                    $this->taverns->add(new Tavern($location));
                    $this->goalLocations[] = $location;
                }
            }
        }
    }

    public function refresh(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->getWidth());

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('$' !== $item[0]) {
                    continue;
                }

                $location = Location::getLocation($x, $y);
                /** @var GoldMine $goldMine */
                $goldMine = $this->goldMines->get($location);

                $heroId = ('-' === $item[1]) ? 0 : (int)$item[1];
                $goldMine->setHeroId($heroId);
            }
        }
    }

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines(): LocationAwareListInterface
    {
        return $this->goldMines;
    }

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(array $friendHeroIds): LocationAwareListInterface
    {
        return $this->goldMines->getFilteredList(
            function(GoldMine $goldMine) use ($friendHeroIds) {
                return !in_array($goldMine->getHeroId(), $friendHeroIds);
            }
        );
    }

    /**
     * @return GoldMine[]
     */
    public function getGoldMinesOf(int $heroId): LocationAwareListInterface
    {
        return $this->goldMines->getFilteredList(
            function(GoldMine $goldMine) use ($heroId) {
                return $goldMine->getHeroId() === $heroId;
            }
        );
    }

    public function getTaverns(): LocationAwareListInterface
    {
        return $this->taverns;
    }

    /**
     * @return LocationGraphInterface
     */
    public function getMap(): LocationGraphInterface
    {
        return $this->map;
    }

    /**
     * @return string[]
     */
    public function getGoalLocations(): array
    {
        return $this->goalLocations;
    }

    /**
     * @return string[]
     */
    public function getWalkableLocations(): array
    {
        return array_diff($this->map->getLocations(), $this->goalLocations);
    }
}
