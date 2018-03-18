<?php

namespace App\Model\Game;

use App\Model\Exceptions\GamePlayException;
use App\Model\GameInterface;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\LocationAwareInterface;
use App\Model\LocationAwareListInterface;
use App\Model\LocationGraphInterface;

class GamePlay implements GamePlayInterface
{
    /**
     * @var GameInterface
     */
    private $game;

    public function __construct(GameInterface $game)
    {
        $this->game = $game;
    }

    public function getHero(): Hero
    {
        return $this->game->getHero();
    }

    /**
     * @return Hero[]
     */
    public function getRivalHeroes(): LocationAwareListInterface
    {
        return $this->game->getRivalHeroes();
    }

    /**
     * @return GoldMine[]
     */
    public function getGoldMines(): LocationAwareListInterface
    {
        return $this->game->getGoldMines();
    }

    /**
     * @return GoldMine[]
     */
    public function getForeignGoldMines(): LocationAwareListInterface
    {
        $friendHeroIds = $this->game->getFriendIds();

        return $this->game->getGoldMines()->getFilteredList(
            function(GoldMine $goldMine) use ($friendHeroIds) {
                return !in_array($goldMine->getHeroId(), $friendHeroIds);
            }
        );
    }

    /**
     * @return GoldMine[]
     */
    public function getGoldMinesOf(int $heroId): LocationAwareListInterface
    {
        return $this->game->getGoldMines()->getFilteredList(
            function(GoldMine $goldMine) use ($heroId) {
                return $goldMine->getHeroId() === $heroId;
            }
        );
    }

    public function getTaverns(): LocationAwareListInterface
    {
        return $this->game->getTaverns();
    }

    /**
     * @return LocationGraphInterface
     */
    public function getMap(): LocationGraphInterface
    {
        return $this->game->getMap();
    }

    /**
     * @return string[]
     */
    public function getTavernAndGoldMineLocations(): array
    {
        return array_merge(
            $this->getGoldMines()->getLocations(),
            $this->getTaverns()->getLocations());
    }

    /**
     * @return string[]
     */
    public function getWalkableLocations(): array
    {
        return array_diff($this->getMap()->getLocations(), $this->getTavernAndGoldMineLocations());
    }

    public function isGameObjectAt(string $location): bool
    {
        if ($this->getRivalHeroes()->exists($location)) {
            return true;
        }

        return in_array($location, $this->getTavernAndGoldMineLocations());
    }

    /**
     * @throws GamePlayException
     */
    public function getGameObjectAt(string $location): LocationAwareInterface
    {
        if ($this->getRivalHeroes()->exists($location)) {
            return $this->getRivalHeroes()->get($location);
        }

        if ($this->getGoldMines()->exists($location)) {
            return $this->getGoldMines()->get($location);
        }

        if ($this->getTaverns()->exists($location)) {
            return $this->getTaverns()->get($location);
        }

        throw new GamePlayException(sprintf('No object at %s', $location));
    }

    public function isGoldMine(string $location): bool
    {
        return $this->game->getGoldMines()->exists($location);
    }

    public function isGoldMineOfHero(string $location): bool
    {
        if (false === $this->isGoldMine($location)) {
            return false;
        }

        $goal = $this->getGoldMines()->get($location);

        return
            ($goal instanceof GoldMine) &&
            ($goal->getHeroId() === $this->getHero()->getId());
    }

    public function isTavern(string $location): bool
    {
        return $this->game->getTaverns()->exists($location);
    }

    public function isRivalHero(string $location): bool
    {
        return $this->game->getRivalHeroes()->exists($location);
    }

    public function getBoardSize(): int
    {
        return $this->game->getBoardSize();
    }
}
