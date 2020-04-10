<?php

namespace console\controllers;

use Yii;
use \console\helpers\Console;
use yii\console\{ Controller, ExitCode };
class GlobalTestController extends ConsoleController
{
    public function actionIndex(){

        $this->startCommand();

        $this->stopCommand();
        return ExitCode::OK;
    }
}