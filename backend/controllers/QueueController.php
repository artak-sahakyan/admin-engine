<?php
namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\Queue;
use backend\models\QueueSearch;
/**
 * CronLogController implements the CRUD actions forCmsCronSchedule model.
 */
class QueueController extends AdminController
{

    public function init()
    {
        $this->modelClass = Queue::class;
    }

    public function actionIndex()
    {
        $searchModel = new QueueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
