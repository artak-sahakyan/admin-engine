<?php
namespace common\widgets;

use yii\base\Widget;

/**
 * Виджет, выводящий выбор кол-ва элементов в таблице
 */
class PageSizesCountWidget extends Widget
{
    /**
     * @var Размеры
     */
    public $sizes = [
        10  => 10, 
        50  => 50, 
        100 => 100
    ];
    /**
     * @var Модель
     */
    public $model;
    /**
     * @var Страница по умолчанию
     */
    public $action = 'index';

    /**
     * {@inheritdoc}
     */
    public function run() {
        return $this->render('grid_page_sizes', [
            'sizes'     => $this->sizes,
            'model'     => $this->model,
            'action'    => $this->action
        ]);
    }
}
