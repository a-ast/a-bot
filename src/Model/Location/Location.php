<?php

namespace App\Model\Location;

use App\Model\LocationInterface;

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

    public static function fromCoordinates(string $coordinates): LocationInterface
    {
        $xy = explode(':', $coordinates);

        return new self($xy[0], $xy[1]);
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

    public function getCoordinates(): string
    {
        return $this->getX() . ':' . $this->getY();
    }
}
