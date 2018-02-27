<?php


namespace App\Model;

interface Locatable
{
    public function getX(): int;

    public function getY(): int;

    public function getDirectDistanceTo(Locatable $location): int;
}