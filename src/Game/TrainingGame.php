<?php

namespace App\Game;

use App\Api\VindiniumApiClient;
use App\Model\Game\Game;
use App\Strategy\PrimitiveStrategy;
use App\Progress\ProgressNotifier;

class TrainingGame
{
    /**
     * @var VindiniumApiClient
     */
    private $apiClient;

    /**
     * @var ProgressNotifier
     */
    private $progressNotifier;

    /**
     * @var PrimitiveStrategy
     */
    private $strategy;

    public function __construct(
        VindiniumApiClient $apiClient,
        ProgressNotifier $progressNotifier,
        PrimitiveStrategy $strategy)
    {
        $this->apiClient = $apiClient;
        $this->progressNotifier = $progressNotifier;
        $this->strategy = $strategy;
    }

    /**
     * @throws \App\Exceptions\GameException
     */
    public function execute(string $apiKey, int $turnCount = null, $mapName = null)
    {
        $initialStateData = $this->apiClient->createTraining($apiKey, $turnCount, $mapName);
        $state = new Game($initialStateData);

        $this->progressNotifier->openUrl($state->getViewUrl());

        $playUrl = $state->getPlayUrl();

        while (false === $state->isFinished()) {
            $direction = $this->strategy->getDirection($state);

            print $direction .PHP_EOL;

            $newState = $this->apiClient->playMove($apiKey, $playUrl, $direction);
            $state->refresh($newState);
        }
    }
}