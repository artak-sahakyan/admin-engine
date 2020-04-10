<?php

namespace backend\events;


use yii\base\Event;

class ControllerModelDeleteEvent extends Event
{
    public $model;
}