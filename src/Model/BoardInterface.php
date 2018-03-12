<?php

namespace App\Model;

use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;

interface BoardInterface
{
    public function getWidth(): int;

    /**
     * @return LocationGraphInterface
     */
    public function getMap(): LocationGraphInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMines(): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(array $friendHeroIds): LocationAwareListInterface;

    /**
     * @return GoldMine[]
     */
    public function getGoldMinesOf(int $heroId): LocationAwareListInterface;

    /**
     * @return Tavern[]
     */
    public function getTaverns(): LocationAwareListInterface;

    /**
     * @return string[]
     */
    public function getGoalLocations(): array;

    public function isGoal(string $location): bool;

    public function getGoal(string $location): LocationAwareInterface;

    /**
     * @return string[]
     */
    public function getWalkableLocations(): array;
}
