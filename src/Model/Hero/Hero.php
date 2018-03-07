<?php

namespace App\Model\Hero;

use App\Model\HeroInterface;
use App\Model\Location\Location;
use App\Model\Location\LocationAwareTrait;
use App\Model\LocationInterface;

class Hero implements HeroInterface
{
    use LocationAwareTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $lifePoints;

    /**
     * @var int
     */
    private $goldPoints;

    /**
     * @var bool
     */
    private $isCrashed;

    /**
     * @var bool
     */
    private $isRespawned;

    /**
     * @var LocationInterface
     */
    private $spawnLocation;

    public function __construct(array $data)
    {
        $this->location = new Location($data['pos']['x'], $data['pos']['y']);
        $this->spawnLocation = new Location($data['spawnPos']['x'], $data['spawnPos']['y']);

        $this->name = $data['name'];
        $this->id = $data['id'];

        $this->refresh($data);
    }

    public function refresh(array $data)
    {
        $this->lifePoints = $data['life'];
        $this->goldPoints = $data['gold'];
        $this->isCrashed = $data['crashed'];

        $newLocation = new Location($data['pos']['x'], $data['pos']['y']);

        $this->isRespawned =
            !$this->location->isNear($newLocation)  &&
            ($newLocation === $this->spawnLocation);

        $this->location = $newLocation;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLifePoints(): int
    {
        return $this->lifePoints;
    }

    public function getGoldPoints(): int
    {
        return $this->goldPoints;
    }

    public function isWalkable()
    {
        return false;
    }

    public function isCrashed(): bool
    {
        return $this->isCrashed;
    }

    public function isOnSpawnLocation(): bool
    {
        return $this->location === $this->spawnLocation;
    }

    public function isRespawned(): bool
    {
        return $this->isRespawned;
    }

    public function __toString()
    {
        return sprintf('Hero [%d: %d] Gold: %d, LP: %d',
            $this->location->getX(), $this->location->getY(), $this->getGoldPoints(), $this->getLifePoints());
    }
}
