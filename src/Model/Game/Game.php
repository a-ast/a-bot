<?php

namespace App\Model\Game;

use App\Model\Direction\DirectionInterface;
use App\Model\GameInterface;
use App\Model\TreasureBoardInterface;
use App\Model\HeroInterface;
use App\Model\Tile\Enemy;
use App\Model\Tile\Hero;
use App\Model\Tile\TileMatrix;
use App\Model\TileInterface;

class Game implements GameInterface
{
    /**
     * @var HeroInterface
     */
    private $hero;

    /**
     * @var array|Enemy[]
     */
    private $enemies;

    /**
     * @var TileMatrix
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
     * @var TreasureBoard
     */
    private $board;

    public function __construct(array $initialState)
    {
        $this->playUrl = $initialState['playUrl'];
        $this->viewUrl = $initialState['viewUrl'];

        $this->hero = new Hero($initialState['hero']);
        $this->createEnemies($initialState['game']['heroes']);

        $boardSize = $initialState['game']['board']['size'];
        $this->board = new TreasureBoard($boardSize, $initialState['game']['board']['tiles']);

        $this->refresh($initialState);
    }

    public function refresh(array $state)
    {
        $this->finished = $state['game']['finished'];

        // @todo: stop if finished?

        $this->hero->refresh($state['hero']);
        $this->refreshEnemies($state['game']['heroes']);

        $this->board->refresh($state['game']['board']['tiles']);
    }

    public function getTileInDirection(TileInterface $tile, DirectionInterface $direction): TileInterface
    {
        $newTile = $this->enemyMatrix->getTileInDirection($tile, $direction);

        if ($newTile instanceof Enemy) {
            return $newTile;
        }

        return $this->board->getTileInDirection($tile, $direction);
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

    public function getBoard(): TreasureBoardInterface
    {
        return $this->board;
    }

    public function getHero(): HeroInterface
    {
        return $this->hero;
    }

    private function createEnemies(array $heroesData)
    {
        $this->enemyMatrix = new TileMatrix();
        $this->enemies = [];

        foreach ($heroesData as $enemyData) {
            $enemyId = $enemyData['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $enemy = new Enemy($enemyData);
            $this->enemies[$enemyId] = $enemy;
            $this->enemyMatrix->addTile($enemy);
        }
    }

    private function refreshEnemies(array $heroesData)
    {
        $this->enemyMatrix->reset();

        foreach ($heroesData as $enemyData) {
            $enemyId = $enemyData['id'];
            if ($enemyId === $this->hero->getId()) {
                continue;
            }

            $enemy = $this->enemies[$enemyId];

            $enemy->refresh($enemyData);
            $this->enemyMatrix->addTile($enemy);
        }
    }
}