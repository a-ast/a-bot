<?php

namespace App\Game;

use App\Model\Game\Game;
use App\Model\Game\GoldMine;
use App\Model\Game\Tavern;
use App\Model\GameInterface;
use App\Model\Location\Location;
use App\Model\Location\LocationAwareList;

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

        $game->setTurn($initialState['game']['turn']);
        $game->setPlayUrl($initialState['playUrl']);
        $game->setViewUrl($initialState['viewUrl']);
        $game->setFinished($initialState['game']['finished']);

        $game->setHero($this->heroBuilder->buildHero($initialState['hero']));
        $this->createRivalHeroes($game, $initialState['game']['heroes']);

        $this->buildObjects($game, $this->getBoardMapArray($initialState));

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

    public function buildObjects(Game $game, array $mapData)
    {
        print join(PHP_EOL, $mapData).PHP_EOL;

        foreach ($mapData as $x => $mapLine) {
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
        $game->setTurn($state['game']['turn']);
        $game->setFinished($state['game']['finished']);

        $this->heroBuilder->updateHero($game->getHero(), $state['hero']);
        $this->updateRivalHeroes($game, $state['game']['heroes']);

        $this->updateGoldMineOwning($game, $this->getBoardMapArray($state));
    }

    private function updateRivalHeroes(Game $game, array $heroesData)
    {
        $heroes = $game->getRivalHeroes();
        $this->createRivalHeroes($game, $heroesData);

        foreach ($heroesData as $heroData) {
            $rivalHeroId = $heroData['id'];
            if ($rivalHeroId === $game->getHero()->getId()) {
                continue;
            }

            $rivalHero = $heroes->getByIndex($rivalHeroId);
            $this->heroBuilder->updateHero($rivalHero, $heroData);
        }
        $heroes->updateLocations();
    }

    public function updateGoldMineOwning(Game $game, array $mapData)
    {
        foreach ($mapData as $x => $mapLine) {
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

    /**
     * @return string[]
     */
    private function getBoardMapArray(array $state): array
    {
        $boardSize = $state['game']['board']['size'];
        $tiles = $state['game']['board']['tiles'];

        return str_split($tiles, 2*$boardSize);
    }

}
