<?php

namespace App\Game;

use App\Exceptions\GameException;
use App\Model\Game\Game;
use App\Model\GameInterface;
use Symfony\Component\Yaml\Yaml;

class GameLoader
{
    /**
     * @var GameBuilder
     */
    private $gameBuilder;

    public function __construct(GameBuilder $gameBuilder)
    {
        $this->gameBuilder = $gameBuilder;
    }

    /**
     * @throws \App\Exceptions\GameException
     */
    public function loadFromFile(string $filePath, int $turn): GameInterface
    {
        if (false === file_exists($filePath)) {
            throw new GameException('File not found: ' . $filePath);
        }

        $game = new Game();

        $data = $this->loadYaml($filePath);
        $turn = $this->findExistingTurn($data, $turn);

        $gameData = $data['game'];
        $this->gameBuilder->buildObjects($game, $gameData['map']);
        $this->gameBuilder->buildHeroes($game, $gameData['hero'], $gameData['rivals']);

        $turnData = $data['turn-'.$turn];
        $this->gameBuilder->updatedHeroes($game, $turnData['hero'], $turnData['rivals']);
        $this->gameBuilder->updateGoldMineOwningFromList($game, $turnData['goldOwners']);

        return $game;
    }

    private function loadYaml(string $filePath): array
    {
        $contents = file_get_contents($filePath);
        $data = Yaml::parse($contents);

        return $data;
    }

    private function findExistingTurn(array &$data, int $startTurn): int
    {
        for ($i = $startTurn; $i > 0; $i--) {
            if (isset($data['turn-'.$i])) {
                return $i;
            }
        }

        // @todo: throw exception
    }
}
