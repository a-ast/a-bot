<?php

namespace App\Model\Location;

class LocationMapBuilder
{
    public function buildFromTextWithEol(string $text): LocationMapInterface
    {
        $mapLines = explode(PHP_EOL, rtrim($text));
        print join(PHP_EOL, $mapLines) . PHP_EOL;

        $map = new LocationMap();

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {

                if ('##' !== $item) {
                    $map->add(new Location($x, $y));
                }
            }
        }

        return $map;
    }
}
