<?php

namespace backend\controllers;

use Yii;
use common\models\ErrorLog;



/**
 * ErrorLogController implements the CRUD actions for ErrorLog model.
 */
class ErrorLogController extends AdminController
{

    public function init()
    {
        $this->modelClass = ErrorLog::class;
    }

    public function actionDeleteAll()
    {
        ErrorLog::deleteAll();

        return $this->redirect('index');
    }

    public function actionBugForm()
    {
        return $this->render('bugform');
    }
}
