<?php


namespace backend\events;


use yii\base\Event;

class ControllerModelSaveEvent extends Event
{

    public $insert;
    public $model;
    public $saved;
    public $oldData;
    public $post = null;
}