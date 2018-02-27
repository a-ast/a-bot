<?php

namespace App\Model;

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

    public function __construct(array $initialState)
    {
        $this->board = new Board($initialState);

        $this->loadInitialState($initialState);
    }

    private function loadInitialState(array $state)
    {
        $this->playUrl = $state['playUrl'];
        $this->viewUrl = $state['viewUrl'];

        $this->refresh($state);
    }

    public function refresh(array $state)
    {
        $this->finished = $state['game']['finished'];
        $this->board->refresh($state);
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

    public function getHero(): Movable
    {
        return $this->board->getHero();
    }
}