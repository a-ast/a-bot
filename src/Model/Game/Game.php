<?php

namespace App\Model\Game;

use App\Model\GameInterface;
use App\Model\HeroInterface;
use App\Model\BoardInterface;
use App\Model\Location\LocationMap;
use App\Model\Hero\Hero;

class Game implements GameInterface
{
    /**
     * @var HeroInterface
     */
    private $hero;

    /**
     * @var array|HeroInterface[]
     */
    private $heroes;

    /**
     * @var LocationMap
     */
    private $enemyMatrix = [];

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
     * @var Board
     */
    private $board;

    public function __construct(array $initialState)
    {
        $this->playUrl = $initialState['playUrl'];
        $this->viewUrl = $initialState['viewUrl'];

        $this->hero = new Hero($initialState['hero']);
        $this->createEnemies($initialState['game']['heroes']);

        $boardSize = $initialState['game']['board']['size'];
        $this->board = new Board($boardSize, $initialState['game']['board']['tiles']);

        $this->refresh($initialState);
    }

    public function refresh(array $state)
    {
        $this->finished = $state['game']['finished'];

        // @todo: stop if finished?

        $this->hero->refresh($state['hero']);
        $this->refreshHeroes($state['game']['heroes']);

        $this->board->refresh($state['game']['board']['tiles']);
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

    public function getBoard(): BoardInterface
    {
        return $this->board;
    }

    public function getHero(): HeroInterface
    {
        return $this->hero;
    }

    public function getHeroes(): array
    {
        return $this->heroes;
    }

    public function getFriendIds(): array
    {
        return [$this->hero->getId()];
    }

    private function createEnemies(array $heroesData)
    {
        $this->enemyMatrix = new LocationMap();
        $this->heroes = [];

        foreach ($heroesData as $heroData) {
            $enemyId = $heroData['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $enemy = new Hero($heroData);
            $this->heroes[$enemyId] = $enemy;
        }
    }

    private function refreshHeroes(array $heroesData)
    {
        //$this->enemyMatrix->reset();

        foreach ($heroesData as $enemyData) {
            $enemyId = $enemyData['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $enemy = $this->heroes[$enemyId];

            $enemy->refresh($enemyData);
            //$this->enemyMatrix->addTile($enemy);
        }
    }
}
