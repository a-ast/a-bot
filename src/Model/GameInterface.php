<?php

namespace App\Model;

use App\Model\Game\LocationAwareListInterface;

interface GameInterface
{
    public function isFinished(): bool;

    public function getPlayUrl(): string;

    public function getViewUrl(): string;

    public function getBoard(): BoardInterface;

    public function getHero(): HeroInterface;

    public function getHeroes(): LocationAwareListInterface;

    /**
     * @return int[]
     */
    public function getFriendIds(): array;
}
