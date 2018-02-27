<?php


namespace App\Model;

interface Locatable extends Existable
{
    public function getX(): int;

    public function getY(): int;

    public function getDirectDistanceTo(Locatable $location): int;
}