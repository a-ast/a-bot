<?php

namespace App\Model;

interface HeroInterface extends TileInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getLifePoints(): int;

    public function getGoldPoints(): int;

    public function isCrashed(): bool;

    public function isRespawned(): bool;

    public function isOnSpawnTile(): bool;
}