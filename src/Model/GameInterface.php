<?php

namespace App\Model;

interface GameInterface
{
    public function isFinished(): bool;

    public function getPlayUrl(): string;

    public function getViewUrl(): string;

    public function getBoard(): TreasureBoardInterface;

    public function getHero(): HeroInterface;

    public function getHeroes(): array;

    public function getNearHero(TileInterface $tile): TileInterface;
}