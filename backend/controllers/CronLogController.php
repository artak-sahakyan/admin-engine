<?php

namespace backend\controllers;

use backend\models\CronLogMessage;
use Yii;
use backend\models\CronLog;
use backend\models\CronLogMessageSearch;
use yii\data\ActiveDataProvider;

/**
 * CronLogController implements the CRUD actions forCmsCronSchedule model.
 */
class CronLogController extends AdminController
{

    public function init()
    {
        $this->modelClass = CronLog::class;
    }

    public function actionView($id)
    {
        $command = CronLog::findOne($id)->command;

        $searchModel = new CronLogMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 100;


        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'command' => $command,
        ]);
    }
}
