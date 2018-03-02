<?php

namespace App\Game;

use App\Api\VindiniumApiClient;
use App\Model\Direction\Compass;
use App\Model\Game\Game;
use App\Progress\ProgressNotifier;
use App\Strategy\StrategyInterface;

class TrainingGame
{
    /**
     * @var VindiniumApiClient
     */
    private $apiClient;

    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var ProgressNotifier
     */
    private $progressNotifier;

    public function __construct(
        VindiniumApiClient $apiClient,
        StrategyInterface $strategy,
        ProgressNotifier $progressNotifier)
    {
        $this->apiClient = $apiClient;
        $this->progressNotifier = $progressNotifier;
        $this->strategy = $strategy;
    }

    /**
     * @throws \App\Exceptions\GameException
     * @throws \Exception
     */
    public function execute(string $apiKey, int $turnCount = null, $mapName = null)
    {
        $initialStateData = $this->apiClient->createTraining($apiKey, $turnCount, $mapName);
        $game = new Game($initialStateData);

        $compass = new Compass();

        $playUrl = $game->getPlayUrl();
        $this->progressNotifier->openUrl($game->getViewUrl());

        $this->strategy->initialize($game);

        while (false === $game->isFinished()) {
            $nextTile = $this->strategy->getNextTile();
            $direction = $compass->getDirectionTo($game->getHero(), $nextTile);

            print $game->getHero() . ' -> '. $nextTile .PHP_EOL;
            print $direction .PHP_EOL;

            $newState = $this->apiClient->playMove($apiKey, $playUrl, $direction);
            $game->refresh($newState);
        }
    }
}