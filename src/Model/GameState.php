<?php

namespace App\Model;

use App\Model\BoardObject\Enemy;
use App\Model\BoardObject\Hero;

class GameState
{
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

    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var array|Enemy[]
     */
    private $enemies;

    public function __construct(array $initialState)
    {
        $board = $initialState['game']['board'];
        $this->board = new Board($board['size'], $board['tiles'], $initialState['hero']['id']);

        $this->loadInitialState($initialState);
    }

    private function loadInitialState(array $state)
    {
        $this->playUrl = $state['playUrl'];
        $this->viewUrl = $state['viewUrl'];

        var_dump($state['hero']);

        $this->hero = new Hero($state['hero']);

        $this->enemies = [];

        foreach ($state['game']['heroes'] as $enemy) {
            $enemyId = $enemy['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $this->enemies[$enemyId] = new Enemy($enemy);
        }

        $this->refresh($state);
    }

    public function refresh(array $state)
    {
        $this->finished = $state['game']['finished'];
        $this->board->refreshBoardObjects($state['game']['board']['tiles']);

        $this->hero->refresh($state['hero']);

        foreach ($state['game']['heroes'] as $enemy) {
            $enemyId = $enemy['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $this->enemies[$enemyId]->refresh($enemy);
        }

        $this->board->refreshCharacters($this->hero, $this->enemies);
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

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getHero(): Hero
    {
        return $this->hero;
    }
}