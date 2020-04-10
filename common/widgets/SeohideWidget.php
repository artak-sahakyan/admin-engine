<?php
namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Виджет, выводящий расшифрованную seohide ссылку
 */
class SeohideWidget extends Widget
{
    /**
     * @var Входящая шифрованная ссылка
     */
    public $url;
    /**
     * @var Текст ссылки
     */
    public $title;
    /**
     * @var Дополнительные параметры
     */
    public $options = array();

    public $target = '_blank';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
    	$options = [
            'data-key'  => base64_encode($this->url),
            'data-type' => 'href',
            'target' => $this->target
        ];

    	$options = array_merge($options,$this->options);
        
        return Html::a($this->title, '#', $options);
    }
}
