<?php
namespace console\controllers;

use common\models\ArticleAdvertisingIntegration;

use common\models\CmsCronSchedule;
use yii\console\{ Controller, ExitCode };
use \console\helpers\Console;


/**
 * for run php yii global-change-advertising-status
 */
class GlobalChangeAdvertisingStatusController extends ConsoleController
{
    public function actionIndex()
    {

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 900);

        $this->startCommand();

        $this->console->output('Смена активности у просроченных интеграций');

        ArticleAdvertisingIntegration::updateAll(['is_active' => 0], ['<', 'end_date', time()]);

        $this->console->output('Завершено');

        $this->stopCommand();
        return ExitCode::OK;

    }
}