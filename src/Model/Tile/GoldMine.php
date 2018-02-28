<?php

namespace App\Model\Tile;

use App\Model\HeroInterface;

class GoldMine extends AbstractTile
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

    public function belongsTo(HeroInterface $hero)
    {
        return $this->getHeroId() === $hero->getId();
    }
}