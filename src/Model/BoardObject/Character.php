<?php

namespace App\Model\BoardObject;

use App\Model\AbstractLocation;

abstract class Character extends AbstractLocation
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

    public function __construct(array $data)
    {
        parent::__construct($data['pos']['x'], $data['pos']['y']);

        $this->name = $data['name'];
        $this->id = $data['id'];
        $this->refresh($data);
    }

    public function refresh(array $data)
    {
        $this->lifePoints = $data['life'];
        $this->goldPoints = $data['gold'];
        $this->setPos($data['pos']);
    }

    private function setPos(array $pos): void
    {
        $this->x = $pos['x'];
        $this->y = $pos['y'];
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

    public function __toString()
    {
        return sprintf('%s [x: %d, y: %d]', static::class, $this->getX(), $this->getY());
    }
}