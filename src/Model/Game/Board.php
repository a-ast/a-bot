<?php

namespace App\Model\Game;

use App\Model\BoardInterface;
use App\Model\Location\Location;
use App\Model\Location\LocationMap;
use App\Model\Location\LocationMapInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

class Board implements BoardInterface
{
    /**
     * @var LocationMapInterface
     */
    protected $map;

    /**
     * @var LocationAwareMapInterface|GoldMine[]
     */
    private $goldMines = [];

    /**
     * @var LocationAwareMapInterface|Tavern[]
     */
    private $taverns = [];

    /**
     * @var int
     */
    private $boardSize;

    public function __construct(int $boardWidth, string $tileData)
    {
        $this->boardSize = $boardWidth;

        $this->map = new LocationMap();
        $this->goldMines = new LocationAwareMap();
        $this->taverns = new LocationAwareMap();

        $this->loadInitialTiles($tileData);
    }

    public function getWidth(): int
    {
        return $this->boardSize;
    }

    private function loadInitialTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->boardSize);

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('##' === $item) {
                    continue;
                }

                // everything belongs to map except of wood
                $location = new Location($x, $y);
                $this->map->add($location);

                if ('$' === $item[0]) {
                    $this->goldMines->add(new GoldMine($location));
                }

                if ('[]' === $item) {
                    $this->taverns->add(new Tavern($location));
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

                $coordinates = (new Location($x, $y))->getCoordinates();
                /** @var GoldMine $goldMine */
                $goldMine = $this->goldMines->getByCoordinates($coordinates);

                $heroId = ('-' === $item[1]) ? 0 : (int)$item[1];
                $goldMine->setHeroId($heroId);
            }
        }
    }

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines(): LocationAwareMapInterface
    {
        return $this->goldMines;
    }

    /**
     * @return GoldMine[]|array
     */
    public function getForeignGoldMines(array $friendHeroIds): LocationAwareMapInterface
    {
        return array_filter($this->goldMines,
            function(GoldMine $goldMine) use ($friendHeroIds) {
                return in_array($goldMine->getHeroId(), $friendHeroIds);
            } );
    }

    public function getTaverns(): LocationAwareMapInterface
    {
        return $this->taverns;
    }

    /**
     * @return LocationMapInterface
     */
    public function getMap(): LocationMapInterface
    {
        return $this->map;
    }
}
