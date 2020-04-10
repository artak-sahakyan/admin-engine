<?php

namespace backend\controllers;

use Yii;
use common\models\Counter;


/**
 * CounterController implements the CRUD actions for Counter model.
 */
class CounterController extends AdminController
{

    public function init()
    {
        $this->modelClass = Counter::class;
    }
}
