<?php

namespace backend\controllers;

use backend\events\ControllerModelSaveEvent;
use Yii;
use common\models\ArticleAdvertisingIntegration;

/**
 * ArticleAdvertisingIntegrationController implements the CRUD actions for ArticleAdvertisingIntegration model.
 */
class ArticleAdvertisingIntegrationController extends AdminController
{

    public function init()
    {
        $this->modelClass = ArticleAdvertisingIntegration::class;
        $this->on(static::MODEL_SAVE_EVENT, [$this, 'modelSave']);
    }

    public function modelSave(ControllerModelSaveEvent $event)
    {
        $event->model->setTimeFormat(['end_date']);
    }
}
