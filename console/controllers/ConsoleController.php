<?php

namespace console\controllers;

use backend\models\CronLog;
use console\helpers\Console;
use console\helpers\logstorage\LogStorageDB;
use yii\console\Controller;

class ConsoleController extends Controller
{
    protected $console;

    /**
     * Set log storage.
     * Init console (logger)
     */
    public function startCommand()
    {
        $model = new CronLog();
        $this->console = new Console(new LogStorageDB($model));
        $this->console->clearScreen();
        $this->console->createLog(static::class);
        $this->console->output('Command ' . $this->console::$command . ' started');
    }

    /**
     * Command completed without mistakes.
     */
    public function stopCommand()
    {
        $this->console->output('Success');

        $this->console->exitLog('done');
    }
}