<?php

namespace backend\controllers;

use Yii;
use common\models\{ Admin, AdminGroup };
use backend\models\AdminUserSearch;
use backend\events\ControllerModelSaveEvent;
use backend\controllers\AdminController;

/**
 * AdminUserController implements the CRUD actions for Admin model.
 */
class AdminUserController extends AdminController
{
    public function init()
    {
        $this->modelClass = Admin::class;
        $this->on(static::MODEL_SAVE_EVENT, [$this, 'adminCreate']);
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'adminSave']);
    }

    public function actionIndex()
    {
        $searchModel = new AdminUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function adminCreate(ControllerModelSaveEvent $event)
    {
        // set default values
        $event->model->setPassword($event->model->password);
        $event->model->generateAuthKey();
    }

    public function adminSave(ControllerModelSaveEvent $event)
    {

        $inputData = Yii::$app->request->post('Admin');

        if(!empty($inputData['adminGroups'])) {
            $group_ids = $inputData['adminGroups'];
            $group_ids = (is_array($group_ids)) ? $group_ids : [$group_ids];

            $event->model->updateLinks($group_ids, 'adminGroups', AdminGroup::class);
        }
    }
}
