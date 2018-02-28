<?php

namespace App\Model\Game;

use App\Model\BoardInterface;
use App\Model\Tile\GoldMine;
use App\Model\Tile\Tavern;
use App\Model\TileInterface;

class Board extends SimpleBoard implements BoardInterface
{
    /**
     * @var array|GoldMine[]
     */
    private $goldMines = [];

    /**
     * @var array|Tavern[]
     */
    private $taverns = [];

    public function __construct(int $boardWidth, string $tileData)
    {
        parent::__construct($boardWidth, $tileData);

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

    protected function onLoadTile(TileInterface $tile)
    {
        if ($tile instanceof GoldMine) {
            $this->goldMines[] = $tile;
        }

        if ($tile instanceof Tavern) {
            $this->taverns[] = $tile;
        }
    }

    private function refreshTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->getWidth());

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