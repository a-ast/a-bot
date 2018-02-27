<?php

namespace App\Model\BoardObject;

use App\Model\AbstractLocation;

class GoldMine extends AbstractLocation
{
    /**
     * @var bool
     */
    private $belongsMe;

    public function __construct(int $x, int $posY, bool $belongsMe)
    {
        parent::__construct($x, $posY);

        $this->belongsMe = $belongsMe;
    }

    public function belongsMe(): bool
    {
        return $this->belongsMe;
    }

    public function setBelongsMe(bool $belongsMe): void
    {
        $this->belongsMe = $belongsMe;
    }
}