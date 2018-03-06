<?php

namespace App\Model\Location;

final class Location implements LocationInterface
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function isNear(LocationInterface $location): bool
    {
        return 1 ===
            (abs($location->getX() - $this->x) +
            abs($location->getY() - $this->y));
    }

    public function isWalkable(): bool
    {
        return false;
    }
}
