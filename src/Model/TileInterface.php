<?php


namespace App\Model;

interface TileInterface
{
    public function getX(): int;

    public function getY(): int;

    public function isNear(TileInterface $tile): bool;

    public function isOn(TileInterface $tile): bool;

    public function getDirectDistanceTo(TileInterface $tile): int;

    public function isWalkable();
}