<?php

namespace App\Model;

use App\Model\Locatable;

class LocationMatrix
{

    /**
     * @var array|Locatable[][]
     */
    private $matrix;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

        $this->reset();
    }

    private function reset()
    {
        for($i = 0; $i < $this->height; $i++) {
            $this->matrix[$i] = array_fill(0, $this->width, null);
        }
    }

    public function getItemByXY(int $x, int $y): Locatable
    {
        return $this->matrix[$x][$y];
    }

    public function setItemByXY(int $x, int $y, Locatable $value)
    {
        $this->matrix[$x][$y] = $value;
    }

    public function getItemByLocation(Locatable $location): Locatable
    {
        return $this->matrix[$location->getX()][$location->getY()];
    }

    public function setItemByLocation(Locatable $location, Locatable $value)
    {
        $this->matrix[$location->getX()][$location->getY()] = $value;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}