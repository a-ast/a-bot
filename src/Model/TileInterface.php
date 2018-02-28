<?php


namespace App\Model;

interface TileInterface
{
    public function getX(): int;

    public function getY(): int;

    public function getDirectDistanceTo(TileInterface $tile): int;

    public function isWalkable();
}