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
        foreach ($strategies as $strategy) {
            $this->strategies[$strategy->getAlias()] = $strategy;
        }

    }

    public function getByAlias(string $alias): StrategyInterface
    {
        return $this->strategies[$alias];
    }
}
