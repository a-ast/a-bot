<?php

namespace App\Model;

use App\Model\Game\Hero;

interface GameInterface
{
    public function isFinished(): bool;

    public function getPlayUrl(): string;

    public function getViewUrl(): string;

    public function getMap(): LocationGraphInterface;

    public function getHero(): Hero;

    public function getRivalHeroes(): LocationAwareListInterface;

    public function getGoldMines(): LocationAwareListInterface;

    public function getTaverns(): LocationAwareListInterface;

    /**
     * @return int[]
     */
    public function getFriendIds(): array;

    public function getGamePlay(): GamePlayInterface;
}
