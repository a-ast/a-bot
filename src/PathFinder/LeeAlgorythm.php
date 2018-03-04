<?php

namespace App\PathFinder;

use App\Model\BoardInterface;
use App\Model\TileInterface;
use SplObjectStorage;

class LeeAlgorythm
{
    /**
     * @var BoardInterface
     */
    private $board;

    /**
     * @var SplObjectStorage
     */
    private $waves;

    public function initialize(BoardInterface $board, array $goals)
    {
        $this->board = $board;

        $this->waves = new SplObjectStorage();

        foreach ($goals as $goal) {
            $this->waves->attach($goal, $this->expandWave($goal));
        }
    }

    /**
     * @return array|TileInterface[]
     */
    public function getPath(TileInterface $fromTile, TileInterface $toTile)
    {
        $visited = $this->expandWave($toTile);

        return $this->findPath($visited, $toTile, $fromTile);
    }

    private function expandWave(TileInterface $toTile): SplObjectStorage
    {
        $visited = new SplObjectStorage();
        $frontier = new SplObjectStorage();
        $tempFrontier = new SplObjectStorage();

        $visited->attach($toTile, 0); // with weight 0
        $frontier->attach($toTile);

        while ($frontier->count() > 0) {

            $tempFrontier->removeAll($tempFrontier);

            foreach ($frontier as $frontierTile) {

                $newDistance = $visited[$frontierTile] + 1;

                $nearTiles = $this->board->getNearTiles($frontierTile);

                foreach ($nearTiles as $nearTile) {

                    // add new tile if not visited
                    if (!isset($visited[$nearTile])) {
                        $tempFrontier->attach($nearTile);
                        $visited->attach($nearTile, $newDistance);

                        continue;
                    }

                    // compare with already visited tile and update if new distance is smaller
                    $oldDistance = $visited[$nearTile];

                    if ($newDistance < $oldDistance) {
                        $visited[$nearTile] = $newDistance;
                    }
                }
            }

            $frontier->removeAll($frontier);
            $frontier->addAll($tempFrontier);
        }

        return $visited;
    }

    private function findPath($waveMap, TileInterface $toTile, TileInterface $fromTile): array
    {
        $pathTile = $fromTile;

        $path = [];

        while ($pathTile !== $toTile) {

            $distance = $waveMap[$pathTile] ?? 100000;

            // we found it
            if ($pathTile->isNear($toTile)) {

                break;
            }

            $nearTiles = $this->board->getNearTiles($pathTile);


            // find min path from near tiles

            $minDistance = 10000;
            foreach ($nearTiles as $nearTile) {
                $newDistance = $waveMap[$nearTile];

                if ($newDistance < $distance && $newDistance < $minDistance) {
                    $minDistance = $newDistance;
                }
            }

            // find min path
            foreach ($nearTiles as $nearTile) {
                $newDistance = $waveMap[$nearTile];

                if ($newDistance <= $minDistance) {
                    $path[] = $nearTile;
                    $pathTile = $nearTile;

                    break;
                }
            }


        }

        return $path;
    }

}