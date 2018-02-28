<?php

namespace App\Model;

use App\Model\Tile\GoldMine;
use App\Model\Direction\Pointable;

class Board implements BoardInterface
{
    /**
     * @var TileMatrix
     */
    private $tiles;

    /**
     * @var array|GoldMine[]
     */
    private $goldMines;

    /**
     * @var int
     */
    private $boardSize;

    public function __construct(int $boardWidth, string $tileData)
    {
        $this->tiles = new TileMatrix();

        $this->boardSize = $boardWidth;

        $this->loadInitialTiles($tileData);
        $this->refresh($tileData);
    }

    public function refresh(string $tileData)
    {
        $this->refreshTiles($tileData);
    }

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines()
    {
        return $this->goldMines;
    }

    public function getTileInDirection(TileInterface $tile, Pointable $direction): TileInterface
    {
        return $this->tiles->getTileInDirection($tile, $direction);
    }

    private function loadInitialTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->boardSize);

        print join(PHP_EOL, $mapLines);

        $this->goldMines = [];

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                $tile = TileFactory::createTile($item, $x, $y);

                $this->tiles->addTile($tile);

                if ($tile instanceof GoldMine) {
                    $this->goldMines[] = $tile;
                }

                // @todo: manage list of taverns
            }
        }
    }

    private function refreshTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->boardSize);

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('$' === $item[0]) {
                    /** @var GoldMine $goldMine */
                    $goldMine = $this->tiles->getTile($x, $y);

                    $heroId = ('0' === $item[1]) ? 0 : (int)$item[1];
                    $goldMine->setHeroId($heroId);
                }
            }
        }
    }
}