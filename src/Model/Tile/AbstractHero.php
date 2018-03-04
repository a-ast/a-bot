<?php

namespace App\Model\Tile;

use App\Model\HeroInterface;

abstract class AbstractHero extends AbstractTile implements HeroInterface
{
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
     * @var int
     */
    private $spawnPosX;

    /**
     * @var int
     */
    private $spawnPosY;

    /**
     * @var bool
     */
    private $isRespawned;

    public function __construct(array $data)
    {
        parent::__construct($data['pos']['x'], $data['pos']['y']);

        $this->spawnPosX = $data['spawnPos']['x'];
        $this->spawnPosY = $data['spawnPos']['y'];

        $this->name = $data['name'];
        $this->id = $data['id'];
        $this->refresh($data);
    }

    public function refresh(array $data)
    {
        $this->lifePoints = $data['life'];
        $this->goldPoints = $data['gold'];
        $this->isCrashed = $data['crashed'];

        $newX = $data['pos']['x'];
        $newY = $data['pos']['y'];

        $this->isRespawned =
            $this->getDirectDistance($newX, $newY) > 1 &&
            ($newX === $this->spawnPosX) &&
            ($newX === $this->spawnPosX);

        $this->x = $newX;
        $this->y = $newY;
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

    public function isOnSpawnTile(): bool
    {
        return
            $this->getX() === $this->spawnPosX &&
            $this->getY() === $this->spawnPosY;
    }

    public function isRespawned(): bool
    {
        return $this->isRespawned;
    }

    public function __toString()
    {
        return sprintf('Hero [%d: %d] Gold: %d, LP: %d',
            $this->getX(), $this->getY(), $this->getGoldPoints(), $this->getLifePoints());
    }

    /**
     * @param $newX
     * @param $newY
     *
     * @return float|int
     */
    private function getDirectDistance($newX, $newY)
    {
        return abs($newX - $this->getX()) + abs($newY - $this->getY());
    }
}