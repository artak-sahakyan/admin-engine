<?php
namespace common\widgets;

use yii\base\Widget;

/**
 * Виджет, выводящий кнопки в crud отмена, сохранить, применить
 */
class ButtonGroupWidget extends Widget
{   
    /**
     * @var Модель, для которой размещаются кнопки
     */
    public $model;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('buttonGroup', [
            'model' => $this->model
        ]);
    }
}
