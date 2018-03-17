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

        $game->setId($initialState['game']['id']);
        $game->setTurn($initialState['game']['turn']);
        $game->setPlayUrl($initialState['playUrl']);
        $game->setViewUrl($initialState['viewUrl']);
        $game->setFinished($initialState['game']['finished']);
        $game->setBoardSize($initialState['game']['board']['size']);

        $this->buildHeroes($game, $initialState['hero'], $initialState['game']['heroes']);

        $this->buildObjects($game, $this->getBoardMapArray($initialState));

        return $game;
    }

    public function buildHeroes(Game $game, array $heroData, array $heroesData)
    {
        $this->buildHero($game, $heroData);
        $this->buildRivalHeroes($game, $heroesData);
    }

    private function buildHero(Game $game, array $data): void
    {
        $game->setHero($this->heroBuilder->buildHero($data));
    }

    private function buildRivalHeroes(Game $game, array $heroesData)
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
        $game->setBoardSize(count($mapData));

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

                    continue;
                }

                if ('[]' === $item) {
                    $tavern = new Tavern($location);
                    $game->addTavern($tavern);

                    continue;
                }
            }
        }
    }

    public function updateGame(Game $game, array $state)
    {
        $game->setTurn($state['game']['turn']);
        $game->setFinished($state['game']['finished']);

        $this->updatedHeroes($game, $state['hero'], $state['game']['heroes']);
        $this->updateGoldMineOwningFromMap($game, $this->getBoardMapArray($state));
    }

    public function updatedHeroes(Game $game, array $heroData, array $heroesData)
    {
        $this->heroBuilder->updateHero($game->getHero(), $heroData);
        $this->updateRivalHeroes($game, $heroesData);
    }

    private function updateRivalHeroes(Game $game, array $heroesData)
    {
        $heroes = $game->getRivalHeroes();
        //$this->buildRivalHeroes($game, $heroesData);

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

    public function updateGoldMineOwningFromMap(Game $game, array $mapData)
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

    public function updateGoldMineOwningFromList(Game $game, array $ownerList)
    {
        foreach ($ownerList as $location => $ownerId) {
            $game->getGoldMines()->get($location)->setHeroId($ownerId);
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
