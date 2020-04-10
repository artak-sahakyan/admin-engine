<?php
namespace common\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Виджет, выводящий расшифрованную seohide ссылку
 */
class ReviewWidget extends Widget
{
    /**
     * @var Имя автора отзыва
     */
    public $name;
    /**
     * @var Содержимое
     */
    public $content;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
    	return $this->render('review', ['name' => $this->name, 'content' => $this->content]);
    }
}
