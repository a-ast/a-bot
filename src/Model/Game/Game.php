<?php

namespace App\Model\Game;

use App\Model\GameInterface;
use App\Model\GamePlayInterface;
use App\Model\Game\Hero;
use App\Model\Location\LocationAwareList;
use App\Model\Location\LocationGraph;
use App\Model\LocationAwareInterface;
use App\Model\LocationAwareListInterface;
use App\Model\LocationGraphInterface;

class Game implements GameInterface
{
    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var LocationAwareListInterface
     */
    private $rivalHeroes;

    /**
     * @var bool
     */
    private $finished;

    /**
     * @var string
     */
    private $playUrl;

    /**
     * @var string
     */
    private $viewUrl;

    /**
     * @var LocationGraphInterface
     */
    protected $map;

    /**
     * @var LocationAwareListInterface|GoldMine[]
     */
    private $goldMines;

    /**
     * @var LocationAwareListInterface|Tavern[]
     */
    private $taverns;

    /**
     * @var LocationAwareListInterface
     */
    private $goals;

    /**
     * @var GamePlayInterface
     */
    private $gamePlay;

    public function __construct()
    {
        $this->map = new LocationGraph();
        $this->hero = new Hero(1, '', '0:0', '0:0');
        $this->goldMines = new LocationAwareList();
        $this->taverns = new LocationAwareList();
        $this->goals = new LocationAwareList();
        $this->rivalHeroes = new LocationAwareList();
        $this->gamePlay = new GamePlay($this);
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function getPlayUrl(): string
    {
        return $this->playUrl;
    }

    public function getViewUrl(): string
    {
        return $this->viewUrl;
    }

    public function getHero(): Hero
    {
        return $this->hero;
    }

    public function setHero(Hero $hero): void
    {
        $this->hero = $hero;
    }

    public function getRivalHeroes(): LocationAwareListInterface
    {
        return $this->rivalHeroes;
    }

    public function addRivalHero(Hero $hero)
    {
        $this->rivalHeroes->add($hero, $hero->getId());
    }

    public function getFriendIds(): array
    {
        return [$this->hero->getId()];
    }

    public function setFinished(bool $finished): void
    {
        $this->finished = $finished;
    }

    public function setPlayUrl(string $playUrl): void
    {
        $this->playUrl = $playUrl;
    }

    public function setViewUrl(string $viewUrl): void
    {
        $this->viewUrl = $viewUrl;
    }

    public function getGoldMines(): LocationAwareListInterface
    {
        return $this->goldMines;
    }

    public function addGoldMine(LocationAwareInterface $goldMine): void
    {
        $this->goldMines->add($goldMine);
        $this->goals->add($goldMine);
    }

    public function getTaverns(): LocationAwareListInterface
    {
        return $this->taverns;
    }

    public function addTavern(LocationAwareInterface $tavern): void
    {
        $this->taverns->add($tavern);
        $this->goals->add($tavern);
    }
    
    public function getGoals(): LocationAwareListInterface
    {
        return $this->goals;
    }

    public function getMap(): LocationGraphInterface
    {
        return $this->map;
    }

    public function getGamePlay(): GamePlayInterface
    {
        return $this->gamePlay;
    }
}
