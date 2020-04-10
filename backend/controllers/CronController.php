<?php

namespace backend\controllers;

use backend\events\ControllerModelDeleteEvent;
use backend\events\ControllerModelSaveEvent;
use backend\models\CronLog;
use Yii;
use common\models\CmsCronSchedule;
use yii2tech\crontab\CronJob;
use yii2tech\crontab\CronTab;

/**
 * CronController implements the CRUD actions for CmsCronSchedule model.
 */
class CronController extends AdminController
{
    const USERNAME = 'ftpuser';

    public function init()
    {
        $this->modelClass = CmsCronSchedule::class;
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'setCron']);
        $this->on(static::MODEL_AFTER_DELETE, [$this, 'deleteCron']);
    }

    public function setCron(ControllerModelSaveEvent $event)
    {
        $model = $event->model;
        /* @var CmsCronSchedule $model */
        $cronTab = new CronTab();
        // $cronTab->username = self::USERNAME;
        if ($event->insert) {
            $cronJob = new CronJob();
            if ($model->is_active) {
                $cronJob->setLine($model->getLine());
                $cronTab->setJobs([$cronJob]);
            } else {
                $cronTab->headLines[] = $model->getLine($model->is_active);
            }

        } else {
            $currents = $cronTab->getCurrentLines();
            $cronTab->removeAll();

            $oldData = $event->oldData;
            $oldCommand = $model->normalizeCommand($oldData['command']);
            $jobs = $model->getJobs($currents, $oldCommand);

            if(!empty($jobs['headLines'])) {
                foreach ($jobs['headLines'] as $headLine) {
                    $cronTab->headLines[] = $headLine;
                }
            }

            ($model->is_active) ? $jobs['jobs'][] = ['line' => $model->getLine()] : $cronTab->headLines[] = $model->getLine($model->is_active);
            $cronTab->setJobs($jobs['jobs']);
        }

        $cronTab->apply();
    }

    public function deleteCron(ControllerModelDeleteEvent $event)
    {
        $model = $event->model;
        $cronTab = new CronTab();
        // $cronTab->username = self::USERNAME;
        $currents = $cronTab->getCurrentLines();
        $cronTab->removeAll();
        $oldCommand = $model->normalizeCommand($model->command);
        $jobs = $model->getJobs($currents, $oldCommand);
        if(!empty($jobs['headLines'])) {
            foreach ($jobs['headLines'] as $headLine) {
                $cronTab->headLines[] = $headLine;
            }
        }
        $cronTab->setJobs($jobs['jobs']);
        $cronTab->apply();
    }

    public function actionCheck($id)
    {
        $model = CmsCronSchedule::findOne($id);
        $command = substr(strrchr('\\', $model->command), 1);
        $cronLog = CronLog::find()->where(['command' => $command])->orderBy('id DESC')->limit(1)->one();

        $result = [
            'content' => null,
            'status' => null,
        ];

        $content = '';
        if ($cronLog->getCronLogMessagesSize($cronLog->id) > 0) {
            foreach ($cronLog->cronLogMessages as $logMessage) {
                $content .= $logMessage['message'] . "\n";
            }
        }
        $result['content'] = $content;
        $result['status']  = $cronLog['status'];

        return json_encode($result);
    }

    public function actionView($id)
    {
        /* @var CmsCronSchedule $model */
        $model = $this->findModel($id);
        $sitePath = Yii::getAlias('@sitePath');
        $commandPath = $model->normalizeCommand();
        $params = ($model->params) ? $model->params : '';
        $command = "php $sitePath/yii {$commandPath} $params > /dev/null 2>/dev/null &";
        exec($command);

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionTest() {

        $cronTab = new CronTab();
        // $cronTab->username = self::USERNAME;
        echo "<pre>";
        print_r($cronTab->getCurrentLines());
        print_r($cronTab->getLines());
        echo "</pre>";die;

        die('z58');
    }
}
