<?php

namespace App\Model\Game;

use App\Model\BoardInterface;
use App\Model\Direction\DirectionInterface;
use App\Model\Direction\Directions;
use App\Model\Tile\TileFactory;
use App\Model\Tile\TileMatrix;
use App\Model\TileInterface;

class Board implements BoardInterface
{
    /**
     * @var TileMatrix
     */
    protected $tiles;

    /**
     * @var int
     */
    private $boardSize;

    public function __construct(int $boardWidth, string $tileData)
    {
        $this->tiles = new TileMatrix();

        $this->boardSize = $boardWidth;

        $this->loadInitialTiles($tileData);
    }

    public function getWidth(): int
    {
        return $this->boardSize;
    }

    public function getTileAt(int $x, int $y): TileInterface
    {
        return $this->tiles->getTile($x, $y);
    }


    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface
    {
        return $this->tiles->getTileInDirection($tile, $direction);
    }

    public function getWalkableNearTiles(TileInterface $tile, TileInterface $includedGoal): array
    {
        $tiles = [];

        foreach (Directions::getWalkableDirections() as $direction) {

            $nearTile = $this->getTileInDirection($tile, $direction);

            if ($nearTile->isWalkable() || $nearTile === $includedGoal) {
                $tiles[] = $this->getTileInDirection($tile, $direction);
            }
        }

        return $tiles;
    }

    private function loadInitialTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->boardSize);

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                $tile = TileFactory::createTile($item, $x, $y);

                $this->tiles->addTile($tile);
                $this->onLoadTile($tile);
            }
        }
    }

    protected function onLoadTile(TileInterface $tile)
    {
    }
}