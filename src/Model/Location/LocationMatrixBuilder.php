<?php

namespace App\Model\Location;

class LocationMatrixBuilder
{
    public function buildFromTextWithEol(string $text): LocationMatrixInterface
    {
        $mapLines = explode(PHP_EOL, rtrim($text));
        print join(PHP_EOL, $mapLines) . PHP_EOL;

        $matrix = new LocationMatrix();

        foreach ($mapLines as $x => $mapLine) {
            $items = str_split($mapLine, 2);

            foreach ($items as $y => $item) {

                if ('##' !== $item) {
                    $matrix->addLocation(new Location($x, $y));
                }
            }
        }

        return $matrix;
    }
}
