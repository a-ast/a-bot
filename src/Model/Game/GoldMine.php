<?php

namespace App\Model\Game;

use App\Model\Location\LocationAwareTrait;
use App\Model\LocationAwareInterface;

class GoldMine implements LocationAwareInterface
{
    use LocationAwareTrait;

    /**
     * @var int
     *
     * Gold mine doesn't belong to any hero by default
     */
    private $heroId = 0;

    public function setHeroId(int $heroId): void
    {
        $this->heroId = $heroId;
    }

    public function getHeroId(): int
    {
        return $this->heroId;
    }
}
