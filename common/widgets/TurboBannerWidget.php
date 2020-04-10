<?php
namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Виджет, выводящий баннерное место для турбостраницы
 */
class TurboBannerWidget extends Widget
{
    /**
     * @var Баннерное место
     */
    public $alias;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
    	return  $this->render('turbo', [
            'alias'           => $this->alias
        ]);
    }
}
