<?php

namespace App\Model;

use App\Model\BoardObject\Character;
use App\Model\BoardObject\Enemy;
use App\Model\BoardObject\GoldMine;
use App\Model\BoardObject\GreatWall;
use App\Model\BoardObject\Hero;
use App\Model\BoardObject\Road;
use App\Model\BoardObject\Tavern;
use App\Model\BoardObject\Unknown;
use App\Model\BoardObject\Wood;
use App\Model\Direction\Pointable;
use App\Model\LocationMatrix;
use Exception;

class Board
{
    /**
     * @var LocationMatrix
     */
    private $tiles;

    /**
     * @var array|GoldMine[]
     */
    private $goldMines;

    /**
     * @var Character[]
     */
    private $characters;

    public function __construct(int $boardSize, string $boardData, int $heroId)
    {
        $this->tiles = new LocationMatrix($boardSize, $boardSize);
        $this->heroId = $heroId;

        $this->loadInitialState($boardData);
    }

    private function loadInitialState(string $boardData)
    {
        $mapLines = str_split($boardData, 2*$this->tiles->getWidth());

        print join(PHP_EOL, $mapLines);

        $this->goldMines = [];

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                $boardObject = $this->createBoardObject($item, $x, $y);

                $this->tiles->setItemByXY($x, $y, $boardObject);

                if ($boardObject instanceof GoldMine) {
                    $this->goldMines[] = $boardObject;
                }
            }
        }
    }

    public function refreshBoardObjects(string $boardData)
    {
        $mapLines = str_split($boardData, 2*$this->tiles->getWidth());

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('$' === $item[0]) {

                    $belongsMe = $item[1] == $this->heroId;

                    /** @var GoldMine $goldMine */
                    $goldMine = $this->tiles->getItemByXY($x, $y);
                    $goldMine->setBelongsMe($belongsMe);
                }
            }
        }
    }

    private function createBoardObject(string $item, int $x, int $y): Locatable
    {
        switch ($item) {
            case '##':
                return new Wood($x, $y);
                break;
            case '[]':
                return new Tavern($x, $y);
                break;
        }

        if ('$' === $item[0]) {
            return new GoldMine($x, $y, false);
        }

        return new Road($x, $y);
    }


    /**
     * @param Hero $hero
     * @param Enemy[] $enemies
     */
    public function refreshCharacters(Hero $hero, array $enemies)
    {
        $this->characters = [];

        // @todo: set hero?

        foreach ($enemies as $enemy) {
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
}