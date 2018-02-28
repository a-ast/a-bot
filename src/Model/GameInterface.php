<?php

namespace App\Model;

use App\Model\Tile\AbstractCharacter;

interface GameInterface
{
    public function isFinished(): bool;

    public function getPlayUrl(): string;

    public function getViewUrl(): string;

    public function getBoard(): BoardInterface;

    public function getHero(): AbstractCharacter;
}