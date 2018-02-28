<?php

namespace App\Model\Tile;

use App\Model\AbstractLocation;

class GoldMine extends AbstractLocation
{
    /**
     * @var int
     */
    private $heroId;

    public function __construct(int $x, int $posY, int $heroId)
    {
        parent::__construct($x, $posY);

        $this->heroId = $heroId;
    }

    public function getHeroId(): int
    {
        return $this->heroId;
    }

    public function setHeroId(int $heroId): void
    {
        $this->heroId = $heroId;
    }

    public function belongsTo(AbstractCharacter $hero)
    {
        return $this->getHeroId() === $hero->getId();
    }
}