<?php

namespace App\Model\Location;

use App\Model\LocationGraphInterface;

class LocationGraphBuilder
{
    public function buildFromTextWithEol(string $text): LocationGraphInterface
    {
        $lines = explode(PHP_EOL, rtrim($text));
        print join(PHP_EOL, $lines) . PHP_EOL;

        $graph = new LocationGraph();

        foreach ($lines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {

                if ('##' !== $item) {
                    $graph->add($x, $y);
                }
            }
        }

        return $graph;
    }
}
