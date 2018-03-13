<?php

namespace App\Model\Game;

use App\Model\Location\LocationAwareTrait;
use App\Model\LocationAwareInterface;


class Hero implements LocationAwareInterface
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
     * @var string
     */
    private $spawnLocation;

    /**
     * @var string
     */
    private $location;

    public function __construct(int $id, string $name, string $location, string $spawnLocation)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->spawnLocation = $spawnLocation;
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

    public function getSpawnLocation(): string
    {
        return $this->spawnLocation;
    }

    public function setLifePoints(int $lifePoints): void
    {
        $this->lifePoints = $lifePoints;
    }

    public function setGoldPoints(int $goldPoints): void
    {
        $this->goldPoints = $goldPoints;
    }

    public function setCrashed(bool $isCrashed): void
    {
        $this->isCrashed = $isCrashed;
    }

    public function setRespawned(bool $isRespawned): void
    {
        $this->isRespawned = $isRespawned;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    public function __toString()
    {
        return sprintf('Hero [%s] Gold: %d, LP: %d',
            $this->location, $this->getGoldPoints(), $this->getLifePoints());
    }
}
