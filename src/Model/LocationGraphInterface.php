<?php

namespace App\Model;

interface LocationGraphInterface
{
    public function add(int $x, int $y): string;

    /**
     * @return string[]
     */
    public function getNearLocations(string $location): array;

    /**
     * @return string[]
     */
    public function getLocations(): array;

    public function isNear(string $from, string $to): bool;
}
