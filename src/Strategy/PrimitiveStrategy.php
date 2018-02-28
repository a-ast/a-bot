<?php

namespace App\Strategy;

use App\Model\GameInterface;

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

    public function getDirection(GameInterface $game): string
    {
        $gold = $this->goldFinder->getClosestGoldMine($game);

        print $game->getHero() .PHP_EOL;
        print sprintf('Found gold at %d:%d Hero: ', $gold->getX(), $gold->getY(), $gold->getHeroId()) . PHP_EOL;

        $availableLocations = $this->moveChooser->getAvailableLocations($game);
        print '  Possible directions: ' . join(', ', array_keys($availableLocations)) . PHP_EOL;

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