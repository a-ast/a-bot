<?php

namespace App\Model;

use App\Model\Game\GoldMine;
use App\Model\Game\Hero;
use App\Model\Game\Tavern;

interface GameInterface
{
    public function getId(): string ;

    public function getTurn(): int;

    public function isFinished(): bool;

    public function getPlayUrl(): string;

    public function getViewUrl(): string;

    public function getBoardSize(): int;

    public function getMap(): LocationGraphInterface;

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
     * @return Tavern[]
     */
    public function getTaverns(): LocationAwareListInterface;

    /**
     * @return int[]
     */
    public function getFriendIds(): array;

    public function getGamePlay(): GamePlayInterface;
}
