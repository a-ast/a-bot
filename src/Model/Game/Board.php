<?php

namespace App\Model\Game;

use App\Model\BoardInterface;
use App\Model\HeroInterface;
use App\Model\Location\Location;
use App\Model\Location\LocationMatrix;
use App\Model\Location\LocationMatrixInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;

class Board implements BoardInterface
{
    /**
     * @var LocationMatrix
     */
    protected $roads;

    /**
     * @var array|GoldMine[]
     */
    private $goldMines = [];

    /**
     * @var array|Tavern[]
     */
    private $taverns = [];

    /**
     * @var int
     */
    private $boardSize;

    public function __construct(int $boardWidth, string $tileData)
    {
        $this->boardSize = $boardWidth;

        $this->roads = new LocationMatrix();

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
                if (' ' === $item) {
                    $road = new Location($x, $y);
                    $this->roads->addLocation($road);

                    continue;
                }

                if ('$' === $item[0]) {
                    $this->goldMines[] = new GoldMine(new Location($x, $y));
                    // @todo: store gold owners
                }

                if ('[]' === $item) {
                    $this->taverns[] = new Tavern(new Location($x, $y));
                    // @todo: store gold owners
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
                if ('$' === $item[0]) {
//                    /** @var GoldMine $goldMine */
//                    $goldMine = $this->roads->getTile($x, $y);
//
//                    $heroId = ('0' === $item[1]) ? 0 : (int)$item[1];
//                    $goldMine->setHeroId($heroId);
                }
            }
        }
    }

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines(): array
    {
        return $this->goldMines;
    }

    /**
     * @return GoldMine[]|array
     */
    public function getForeignGoldMines(): array
    {
        return $this->goldMines;

//        return array_filter($this->goldMines,
//            function(GoldMine $goldMine) use ($exceptHero) {
//                return $goldMine->getHeroId() !== $exceptHero->getId();
//            } );
    }

    public function getTaverns(): array
    {
        return $this->taverns;
    }

    /**
     * @return LocationMatrixInterface
     */
    public function getRoads(): LocationMatrixInterface
    {
        return $this->roads;
    }
}
