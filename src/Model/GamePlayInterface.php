<?php

namespace App\Model;

use App\Model\Exceptions\GamePlayException;
use App\Model\Game\GoldMine;
use App\Model\Game\Hero;
use App\Model\Game\Tavern;

interface GamePlayInterface
{

    public function getHero(): Hero;

    /**
     * @return Hero[]
     */
    public function getRivalHeroes(): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMines(): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMinesOf(int $heroId): LocationAwareListInterface;

    /**
     * @return Tavern[]
     */
    public function getTaverns(): LocationAwareListInterface;

    /**
     * @return LocationGraphInterface
     */
    public function getMap(): LocationGraphInterface;

    /**
     * @return string[]
     */
    public function getTavernAndGoldMineLocations(): array;

    /**
     * @return string[]
     */
    public function getWalkableLocations(): array;

    public function isGameObjectAt(string $location): bool;

    public function getGameObjectAt(string $location): LocationAwareInterface;

    public function isGoldMine(string $location): bool;

    public function isGoldMineOfHero(string $location): bool;

    public function isTavern(string $location): bool;

    public function isRivalHero(string $location): bool;

    public function getBoardSize(): int;
}
