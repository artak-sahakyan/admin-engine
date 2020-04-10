<?php


namespace backend\events;


use yii\base\Event;

class ControllerBeforeRenderEvent extends Event
{
    public $args;
    public $insert;
}