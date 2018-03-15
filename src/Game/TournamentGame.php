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

    /**
     * @var GameBuilder
     */
    private $gameBuilder;

    /**
     * @var GameDumper
     */
    private $gameDumper;

    public function __construct(
        VindiniumApiClient $apiClient,
        GameBuilder $gameBuilder,
        GameDumper $gameDumper,
        ProgressNotifier $progressNotifier)
    {
        $this->apiClient = $apiClient;
        $this->gameBuilder = $gameBuilder;
        $this->gameDumper = $gameDumper;
        $this->progressNotifier = $progressNotifier;
    }

    public function setStrategy(StrategyInterface $strategy)
    {
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
        $compass = new Compass();

        $game = $this->gameBuilder->buildGame($initialStateData);

        $playUrl = $game->getPlayUrl();
        $this->progressNotifier->openUrl($game->getViewUrl());

        $this->strategy->initialize($game->getGamePlay());

        while (false === $game->isFinished()) {

            print '['. $game->getTurn() .']'.PHP_EOL;
            print $this->gameDumper->dumpHero($game->getHero()) . PHP_EOL;
            print $this->gameDumper->dumpHeroes($game) . PHP_EOL;

            $nextLocation = $this->strategy->getNextLocation();


            $direction = $compass->getDirectionTo($game->getHero()->getLocation(), $nextLocation);

            print $direction.PHP_EOL.PHP_EOL;

            $newState = $this->apiClient->playMove($apiKey, $playUrl, $direction);

            $this->gameBuilder->updateGame($game, $newState);
        }
    }
}
