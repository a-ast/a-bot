<?php

namespace App\Game;

use App\Api\VindiniumApiClient;
use App\Model\Game\Compass;
use App\Model\Game\Game;
use App\Progress\ProgressNotifier;
use App\Strategy\StrategyInterface;

class TournamentGame
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
    public function executeTraining(string $apiKey, int $turnCount = null, $mapName = null)
    {
        $initialStateData = $this->apiClient->createTraining($apiKey, $turnCount, $mapName);

        $this->execute($apiKey, $initialStateData);
    }

    /**
     * @throws \App\Exceptions\GameException
     * @throws \Exception
     */
    public function executeArena(string $apiKey)
    {
        $initialStateData = $this->apiClient->createArena($apiKey);

        $this->execute($apiKey, $initialStateData);
    }

    /**
     * @param string $apiKey
     * @param $initialStateData
     *
     * @throws \App\Exceptions\GameException
     * @throws \Exception
     */
    private function execute(string $apiKey, $initialStateData): void
    {
        $game = new Game($initialStateData);
        $compass = new Compass();

        $playUrl = $game->getPlayUrl();
        $this->progressNotifier->openUrl($game->getViewUrl());

        $this->strategy->initialize($game);

        while (false === $game->isFinished()) {

            $nextLocation = $this->strategy->getNextLocation();

            print $game->getHero()->getLocation().' -> '.$nextLocation.PHP_EOL;

            $direction = $compass->getDirectionTo($game->getHero()->getLocation(), $nextLocation);

            print $direction.PHP_EOL.PHP_EOL;

            $newState = $this->apiClient->playMove($apiKey, $playUrl, $direction);
            $game->refresh($newState);
        }
    }
}
