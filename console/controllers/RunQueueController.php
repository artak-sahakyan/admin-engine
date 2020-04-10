<?php
namespace console\controllers;

use yii\console\ExitCode;

class RunQueueController extends ConsoleController
{
    private $yiiPath;
    /**
     * find php
     * @var array
     */
    private $phpBin = [
        '/usr/bin/php',
        '/usr/local/bin/php',
    ];

    public $stop = 0;

    public function options($actionID)
    {
        return ['stop'];
    }
    
    public function optionAliases()
    {
        return ['stop' => 'stop'];
    }

    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        if ($this->stop) {
            $this->stopListens();
        } else {
            $this->yiiPath = \Yii::getAlias('@sitePath') . '/yii';

            //     $pids = $this->getProcesses();

            //     if(count($pids) > 4) {
            //         $this->stopListens();
            //         $this->runListen();
            //     } elseif(count($pids) == 4) {
            //         $this->console->output('Демон уже запущен');
            //     } else {
            //         $this->runListen();
            //     }
            // }

            $this->runListen();
        }

        return ExitCode::OK;
    }

    // private function getProcesses()
    // {
    //     exec("ps aux | grep -i 'queue/listen'", $pids);
    //     return $pids;
    // }

    private function runListen()
    {
        $yiiPath = $this->yiiPath;
        $phpPath = $this->phpPath();
        exec("$phpPath $yiiPath queue/run", $output, $returnVar);
    }

    private function stopListens()
    {
        exec("pkill -f 'queue/listen'");
    }

    /**
     * Return php path.
     *
     * @return string
     */
    private function phpPath()
    {
        foreach ($this->phpBin as $phpPath) {
            if (file_exists($phpPath)) {
                return $phpPath;
            }
        }

        throw new \Error('php not found');
    }
}
