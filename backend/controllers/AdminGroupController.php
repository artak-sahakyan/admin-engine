<?php

namespace backend\controllers;

use backend\events\ControllerModelSaveEvent;
use Yii;
use common\models\AdminGroup;
use backend\controllers\AdminController;

/**
 * AdminGroupController implements the CRUD actions for AdminGroup model.
 */
class AdminGroupController extends AdminController
{
    public function init()
    {
        $this->modelClass = AdminGroup::class;
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'accessActionUpdate']);
    }

    public function actionCreate()
    {
        $this->getAccess();

        return parent::actionCreate();
    }

    public function actionUpdate($id)
    {
        $this->getAccess($id);

        return parent::actionUpdate($id);
    }

    public function accessActionUpdate(ControllerModelSaveEvent $event)
    {
        if (Yii::$app->request->isPost) {
            if (AdminGroup::isAdmin()) {
                $adminGroup = AdminGroup::findOne(['id' => $event->model->id]);
                $allowActions = Yii::$app->request->post('allow_actions');
                $adminGroup['allow_actions'] = $allowActions;
                $adminGroup->save();
            }
        }
    }

    /**
     * Scan controllers on public actions.
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getPublicActions()
    {
        $controllerDir = dirname(__FILE__);
        $namespace = __NAMESPACE__ . '\\';

        $files = scandir($controllerDir);
        $files = array_diff($files, ['AdminController.php', '..', '.']);

        $actions = [];
        foreach ($files as $file) {
            if (is_file($controllerDir . '/' . $file)) {
                $pattern = '#' . preg_quote('.php', '#') . '$#';
                $class = preg_replace($pattern, '', $file);

                $reflection = new \ReflectionClass($namespace . $class);
                $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

                $controller = '';
                foreach ($methods as $method) {
                    if (!$controller) {
                        $pattern = '#(?:^' . preg_quote($namespace, '#') . ')|(?:Controller$)#';
                        $controller = preg_replace($pattern, '', $method->class);
                    }

                    if (preg_match('#^action[A-Z]+#', $method->name)) {
                        $action = $method->name;
                        $action = preg_replace('#^action#', '', $action);

                        $actions[$controller][] = $action;
                    }
                }
            }
        }

        return $actions;
    }

    /**
     * Give to template actions.
     *
     * @param null $id
     * @throws \ReflectionException
     */
    private function getAccess($id = null)
    {
        if ($id) {
            $adminGroup = AdminGroup::findOne(['id' => $id]);
        } else {
            $adminGroup = new AdminGroup;
        }
        $actions = $this->getPublicActions();
        $this->view->params['actions'] = $actions;
        $this->view->params['adminGroup'] = $adminGroup;
    }

}
