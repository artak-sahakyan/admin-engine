<?php
namespace console\controllers;

use common\models\ErrorLog;
use Yii;
use common\models\{ Article };
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;
use common\helpers\FilesHelper;

/**
 * run   php yii global-create-error-logs
 * Class GlobalCreateErrorLogsController
 * @package console\controllers
 */
class GlobalCreateErrorLogsController extends ConsoleController
{

    public function actionIndex()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0);

        FilesHelper::deleteErrorLogs();

        $this->startCommand();

        $this->console->output('Создаю error log...');

        self::processLogs();

        $this->console->output('Завершено');

        $this->stopCommand();
        return ExitCode::OK;
    }

    protected function processLogs()
    {
        $logs = ErrorLog::find()->orderBy('id DESC');

        $countLogs = $logs->count();

        $this->console->output('Всего ' . $countLogs . 'ошибок');

        $counterProgress = 1;
        $this->console->startProgress($counterProgress, $countLogs);

        $file = FilesHelper::createErrorLogFile();

        foreach ($logs->each(100) as $log) {
            FilesHelper::writeErrorLogs($file, $log, $countLogs);
            Console::updateProgress($counterProgress++, $countLogs);
        }

        ErrorLog::deleteAll();

        $this->console->endProgress();
        return true;
    }
}
