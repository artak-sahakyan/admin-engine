<?php

namespace backend\controllers;

use backend\events\ControllerModelSaveEvent;
use Yii;
use common\models\ArticleErrorInfo;

/**
 * ArticleErrorInfoController implements the CRUD actions for ArticleErrorInfo model.
 */
class ArticleErrorInfoController extends AdminController
{
    public function init()
    {
        $this->modelClass = ArticleErrorInfo::class;
        $this->on(static::MODEL_SAVE_EVENT, [$this, 'modelSave']);
    }

    public function actionCreate()
    {
        $this->redirect(['index']);
    }

    public function modelSave(ControllerModelSaveEvent $event)
    {
        $event->model->setTimeFormat(['date_send']);
    }
}
