<?php


namespace App\Progress;

use Symfony\Component\Process\Process;

class ProgressNotifier
{
    public function openUrl(string $url)
    {
        print $url .PHP_EOL;

        $process = new Process('open '.$url);
        $process->start();
    }
}
