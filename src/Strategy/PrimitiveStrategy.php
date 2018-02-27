<?php

namespace App\Strategy;

use App\Model\Direction\Directions;
use App\Model\GameState;

class PrimitiveStrategy
{
    /**
     * @var GoldFinder
     */
    private $goldFinder;

    /**
     * @var MoveChooser
     */
    private $moveChooser;

    public function __construct(GoldFinder $goldFinder, MoveChooser $moveChooser)
    {
        $this->goldFinder = $goldFinder;
        $this->moveChooser = $moveChooser;
    }

    public function getDirection(GameState $gameState): string
    {
        $gold = $this->goldFinder->getClosestGoldMine($gameState);

        print $gameState->getHero() .PHP_EOL;
        print sprintf('Found gold at %d:%d My: ', $gold->getX(), $gold->getY(), (int)$gold->belongsMe()) . PHP_EOL;

        $availableLocations = $this->moveChooser->getAvailableLocations($gameState);
        print '  Possible directions: ' . join(', ', array_keys($availableLocations)) . PHP_EOL;

        $minDistance = 10000;

        $preferableDirection = Directions::getNoDirection()->getTitle();


        $possibleDirections = [];

        foreach ($availableLocations as $direction => $availableLocation) {
            $distanceToGold = $availableLocation->getDirectDistanceTo($gold);

            if (0 === $distanceToGold) {
                print '  Found gold' . PHP_EOL;
                // take this direction because it is gold worth
                return $direction;
            }

            $possibleDirections[$direction] = $distanceToGold;
        }

        $minDistance = min($possibleDirections);
        $possibleDirections = array_keys(array_filter($possibleDirections,
            function($item) use ($minDistance) { return $item === $minDistance; }));

        print '  Possible directions filtered: ' . join(', ', $possibleDirections) . PHP_EOL;

        $preferableDirection = $possibleDirections[array_rand($possibleDirections)];


        print '  I go to: ' . $preferableDirection . PHP_EOL;
        print PHP_EOL;

        return $preferableDirection;
    }
}