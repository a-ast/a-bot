<?php

namespace App\Model;

use App\Model\Tile\AbstractCharacter;
use App\Model\Tile\Enemy;
use App\Model\Tile\GoldMine;
use App\Model\Tile\GreatWall;
use App\Model\Tile\Hero;
use App\Model\Tile\Road;
use App\Model\Tile\Tavern;
use App\Model\Tile\Unknown;
use App\Model\Tile\Wood;
use App\Model\Direction\Pointable;
use App\Model\LocationMatrix;
use Exception;

class Board
{
    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var array|Enemy[]
     */
    private $enemies;

    /**
     * @var LocationMatrix
     */
    private $tiles;

    /**
     * @var array|GoldMine[]
     */
    private $goldMines;

    /**
     * @var AbstractCharacter[]
     */
    private $characters;

    public function __construct(array $initialState)
    {
        $this->hero = new Hero($initialState['hero']);
        $this->createEnemies($initialState['game']['heroes']);

        $boardSize = $initialState['game']['board']['size'];
        $this->tiles = new LocationMatrix($boardSize, $boardSize);
        $this->loadInitialTiles($initialState['game']['board']['tiles']);

        $this->refresh($initialState);
    }

    public function refresh(array $state)
    {
        $this->refreshTiles($state['game']['board']['tiles']);
        $this->hero->refresh($state['hero']);
        $this->refreshEnemies($state['game']['heroes']);
        $this->putCharactersOnBoard();
    }

    private function createEnemies(array $heroesData)
    {
        $this->enemies = [];

        foreach ($heroesData as $enemy) {
            $enemyId = $enemy['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $this->enemies[$enemyId] = new Enemy($enemy);
        }
    }

    private function refreshEnemies(array $heroesData)
    {
        foreach ($heroesData as $enemy) {
            $enemyId = $enemy['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $this->enemies[$enemyId]->refresh($enemy);
        }
    }

    private function loadInitialTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->tiles->getWidth());

        print join(PHP_EOL, $mapLines);

        $this->goldMines = [];

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                $location = LocationFactory::createLocation($item, $x, $y);

                $this->tiles->setItemByXY($x, $y, $location);

                if ($location instanceof GoldMine) {
                    $this->goldMines[] = $location;
                }

                // @todo: manage list of taverns
            }
        }
    }

    private function refreshTiles(string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$this->tiles->getWidth());

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('$' === $item[0]) {

                    $belongsMe = $item[1] == $this->hero->getId();

                    /** @var GoldMine $goldMine */
                    $goldMine = $this->tiles->getItemByXY($x, $y);
                    $goldMine->setBelongsMe($belongsMe);
                }
            }
        }
    }

    private function putCharactersOnBoard()
    {
        $this->characters = [];

        // @todo: set hero?

        foreach ($this->enemies as $enemy) {
            $this->characters[$enemy->getX()][$enemy->getY()] = $enemy;
        }
    }

    /**
     * @return GoldMine[]|array
     */
    public function getGoldMines()
    {
        return $this->goldMines;
    }

    private function getLocationByXY(int $x, int $y): Locatable
    {
        if (isset($this->characters[$x][$y])) {
            return $this->characters[$x][$y];
        }

        return $this->tiles->getItemByXY($x, $y);
    }

    public function getLocationInDirection(Locatable $location, Pointable $direction): Locatable
    {
        $newX = $location->getX() + $direction->getShiftX();
        $newY = $location->getY() + $direction->getShiftY();

        try {
            return $this->getLocationByXY($newX, $newY);
        } catch (Exception $e) {
            return new GreatWall(0, 0);
        }
    }

    public function getHero(): Movable
    {
        return $this->hero;
    }
}