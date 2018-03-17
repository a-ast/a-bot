<?php


namespace App\Progress;

use Symfony\Component\Process\Process;

class ProgressNotifier
{
    public function notify(string $url, string $gameFilePath)
    {
        print $url . PHP_EOL;
        $tailCommad = 'tab "tail -f '.$gameFilePath.'"';
        print $tailCommad. PHP_EOL;

        $process = new Process('open '.$url);
        $process->start();

        $process = new Process($tailCommad);
        $process->start();
    }
}
