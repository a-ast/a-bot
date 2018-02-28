<?php

namespace App\PathFinder;

use App\Model\BoardInterface;
use App\Model\TileInterface;
use SplObjectStorage;

class LeeAlgorythm
{
    public function getPath(BoardInterface $board, TileInterface $fromTile, TileInterface $toTile)
    {
        $visited = $this->expandWave($board, $fromTile, $toTile);

        $pathTile = $fromTile;

        $path = [];

        while ($pathTile !== $toTile) {

            $distance = $visited[$pathTile];

            $nearTiles = $board->getWalkableNearTiles($pathTile, $toTile);
            foreach ($nearTiles as $nearTile) {
                $newDistance = $visited[$nearTile];

                if ($newDistance < $distance) {
                    $path[] = $nearTile;
                    $pathTile = $nearTile;

                    break;
                }
            }
        }

        var_dump($visited->count());
        var_dump(count($path));
    }

    private function expandWave(BoardInterface $board, TileInterface $fromTile, TileInterface $toTile): SplObjectStorage
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

                $nearTiles = $board->getWalkableNearTiles($frontierTile, $fromTile);

                foreach ($nearTiles as $nearTile) {

                    // add new tile
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


}