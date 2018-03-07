<?php

namespace App\Model;

interface HeroInterface extends LocationAwareInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getLifePoints(): int;

    public function getGoldPoints(): int;

    public function isCrashed(): bool;

    public function isRespawned(): bool;

    public function isOnSpawnLocation(): bool;
}
