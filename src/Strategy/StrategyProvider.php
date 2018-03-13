<?php

namespace App\Strategy;

class StrategyProvider
{
    /**
     * @var array
     */
    private $strategies;

    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getByAlias(string $alias): StrategyInterface
    {
        return $this->strategies[$alias];
    }
}
