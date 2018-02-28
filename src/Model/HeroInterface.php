<?php

namespace App\Model;

interface HeroInterface extends TileInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getLifePoints(): int;

    public function getGoldPoints(): int;
}