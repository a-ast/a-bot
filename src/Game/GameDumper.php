<?php

namespace App\Game;

use App\Model\Game\Hero;
use App\Model\GameInterface;
use App\Model\Location\Location;
use DateTimeImmutable;
use Symfony\Component\Yaml\Yaml;

class GameDumper
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var DateTimeImmutable
     */
    private $time;

    public function __construct(string $gameFilesPath)
    {
        $this->basePath = $gameFilesPath;

        $this->time = new DateTimeImmutable();
    }

    public function dumpInitialState(GameInterface $game)
    {
        $heroes = [];

        foreach ($game->getRivalHeroes() as $hero) {
            $heroes[] = $this->dumpHeroInitialStateToArray($hero);
        }

        $data = [
            'game' => [
                'viewUrl' => $game->getViewUrl(),
                'map' => $this->dumpMapAsArray($game),
                'hero' => $this->dumpHeroInitialStateToArray($game->getHero()),
                'rivals' => $heroes,
                'friendIds' > $game->getFriendIds(),
            ]
        ];

        $this->dumpToFile($game, $data);
    }

    public function dumpTurn(GameInterface $game, array $strategyResults)
    {
        $heroes = [];

        foreach ($game->getRivalHeroes() as $hero) {
            $heroes[] = $this->dumpHeroStepStateToArray($hero);
        }

        $goldOwners = [];

        foreach ($game->getGoldMines() as $goldMine) {
            $goldOwners[$goldMine->getLocation()] = $goldMine->getHeroId();
        }

        $data = [
            'turn-' . $game->getTurn() => [
                'hero' => $this->dumpHeroStepStateToArray($game->getHero()),
                'rivals' => $heroes,
                'goldOwners' => $goldOwners,
                'strategy' => $strategyResults,
            ],
        ];

        $this->dumpToFile($game, $data);
    }

    private function dumpHeroInitialStateToArray(Hero $hero)
    {
        return [
            'id' => $hero->getId(),
            'name' => $hero->getName(),
            'pos' => [
                'x' => Location::getXY($hero->getLocation())[0],
                'y' => Location::getXY($hero->getLocation())[1],
            ],
            'spawnPos' => [
                'x' => Location::getXY($hero->getSpawnLocation())[0],
                'y' => Location::getXY($hero->getSpawnLocation())[1],
            ],
        ];
    }

    private function dumpHeroStepStateToArray(Hero $hero)
    {
        return [
            'id' => $hero->getId(),
            'pos' => [
                'x' => Location::getXY($hero->getLocation())[0],
                'y' => Location::getXY($hero->getLocation())[1],
            ],
            'life' => $hero->getLifePoints(),
            'gold' => $hero->getGoldPoints(),
            'crashed' => $hero->isCrashed(),
        ];
    }

    private function dumpMapAsArray(GameInterface $game): array
    {
        $size = $game->getBoardSize();
        $lines = [];

        for ($x = 0; $x < $size; $x++) {

            $line = [];
            for ($y = 0; $y < $size; $y++) {

                $location = Location::getLocation($x, $y);

                if ($game->getTaverns()->exists($location)) {
                    $line[] = '[]';

                    continue;
                }

                if ($game->getGoldMines()->exists($location)) {
                    $line[] = '$-';

                    continue;
                }

                if ($game->getMap()->exists($location)) {
                    $line[] = '  ';

                    continue;
                }

                $line[] = '##';
            }

            $lines[] = join('', $line);
        }

        return $lines;

    }

    private function dumpToFile(GameInterface $game, $data): void
    {
        file_put_contents($this->getFilePath($game), Yaml::dump($data, 4), FILE_APPEND);
    }

    public function getFilePath(GameInterface $game): string
    {
        $fileName = sprintf('%s-%s-%d---%s',
            $this->time->format('Y-m-d-H-i-s'),
            $game->getHero()->getName(),
            $game->getHero()->getId(),
            $game->getId()
        );

        return $this->basePath . $fileName . '.yaml';
    }
}
