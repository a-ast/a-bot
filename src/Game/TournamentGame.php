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
    public function executeTraining(string $apiKey, int $turnCount = null, $mapName = null, bool $dumpGame = false)
    {
        $this->gameDumper->setMode(GameDumper::GAME_MODE_TRAINING);

        $initialStateData = $this->apiClient->createTraining($apiKey, $turnCount, $mapName);

        $this->execute($apiKey, $initialStateData, $dumpGame);
    }

    /**
     * @throws \App\Exceptions\GameException
     * @throws \Exception
     */
    public function executeArena(string $apiKey, bool $dumpGame = false)
    {
        $this->gameDumper->setMode(GameDumper::GAME_MODE_ARENA);

        $initialStateData = $this->apiClient->createArena($apiKey);

        $this->execute($apiKey, $initialStateData, $dumpGame);
    }

    /**
     * @throws \App\Exceptions\GameException
     * @throws \Exception
     */
    private function execute(string $apiKey, array $initialStateData, bool $dumpGame = false): void
    {
        $compass = new Compass();

        $game = $this->gameBuilder->buildGame($initialStateData);

        if ($dumpGame) {
            $this->gameDumper->dumpInitialState($game);
        }

        $this->progressNotifier->notify($game->getViewUrl(), $this->gameDumper->getFilePath($game));
        $playUrl = $game->getPlayUrl();

        $this->strategy->initialize($game->getGamePlay());

        while (false === $game->isFinished()) {

            $nextLocation = $this->strategy->getNextLocation();
            $direction = $compass->getDirectionTo($game->getHero()->getLocation(), $nextLocation);

            $strategyResults = array_merge($this->strategy->getTacticStatistics() ,[
                'nextLocation' => $nextLocation,
                'direction' => $direction,
            ]);

            if ($dumpGame) {
                $this->gameDumper->dumpTurn($game, $strategyResults);
            }

            $newState = $this->apiClient->playMove($apiKey, $playUrl, $direction);
            $this->gameBuilder->updateGame($game, $newState);
        }
    }
}
