<?php

namespace App\Game;

use App\Model\Game\Game;
use App\Model\Game\GoldMine;
use App\Model\Game\Hero;
use App\Model\Game\Tavern;
use App\Model\GameInterface;
use App\Model\Location\Location;

class GameBuilder
{
    /**
     * @var \App\Game\HeroBuilder
     */
    private $heroBuilder;

    public function __construct(HeroBuilder $heroBuilder)
    {
        $this->heroBuilder = $heroBuilder;
    }

    public function buildGame(array $initialState): GameInterface
    {
        $game = new Game();

        $game->setPlayUrl($initialState['playUrl']);
        $game->setViewUrl($initialState['viewUrl']);
        $game->setFinished($initialState['game']['finished']);

        $game->setHero($this->heroBuilder->buildHero($initialState['hero']));
        $this->createRivalHeroes($game, $initialState['game']['heroes']);

        $boardSize = $this->getBoardSize($initialState);
        $this->createObjects($game, $boardSize, $initialState['game']['board']['tiles']);

        return $game;
    }

    private function createRivalHeroes(Game $game, array $heroesData)
    {
        foreach ($heroesData as $heroData) {
            $heroId = $heroData['id'];

            if ($heroId === $game->getHero()->getId()) {
                continue;
            }

            $game->addRivalHero($this->heroBuilder->buildHero($heroData));
        }
    }

    private function createObjects(Game $game, int $boardSize, string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$boardSize);

        print join(PHP_EOL, $mapLines).PHP_EOL;

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('##' === $item) {
                    continue;
                }

                // everything belongs to map except of wood
                $location = $game->getMap()->add($x, $y);

                if ('$' === $item[0]) {
                    $goldMine = new GoldMine($location);
                    $game->addGoldMine($goldMine);
                }

                if ('[]' === $item) {
                    $tavern = new Tavern($location);
                    $game->addTavern($tavern);
                }
            }
        }
    }

    public function updateGame(Game $game, array $state)
    {
        $game->setFinished($state['game']['finished']);

        $this->heroBuilder->updateHero($game->getHero(), $state['hero']);
        $this->updateRivalHeroes($game, $state['game']['heroes']);
        $this->updateGoldMineOwning($game, $this->getBoardSize($state), $state['game']['board']['tiles']);
    }

    private function updateRivalHeroes(Game $game, array $heroesData)
    {
        foreach ($heroesData as $heroData) {
            $rivalHeroId = $heroData['id'];
            if ($rivalHeroId === $game->getHero()->getId()) {
                continue;
            }

            $rivalHero = $game->getRivalHeroes()->getByIndex($rivalHeroId);
            $this->heroBuilder->updateHero($rivalHero, $heroData);
        }
    }

    public function updateGoldMineOwning(Game $game, int $boardSize, string $tilesData)
    {
        $mapLines = str_split($tilesData, 2*$boardSize);

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {
                if ('$' !== $item[0]) {
                    continue;
                }

                $location = Location::getLocation($x, $y);
                /** @var GoldMine $goldMine */
                $goldMine = $game->getGoldMines()->get($location);

                $heroId = ('-' === $item[1]) ? 0 : (int)$item[1];
                $goldMine->setHeroId($heroId);
            }
        }
    }

    private function getBoardSize(array $state): int
    {
        return $state['game']['board']['size'];
    }
}
