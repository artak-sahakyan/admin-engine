<?php

namespace common\widgets;

use yii\base\Exception;
use yii\base\Widget;
use common\traits\WidgetRenderPriorityTrait;

/**
 * Class ContentsNavigation
 * @package common\widgets
 */
class ContentNavigationWidget extends Widget
{
    use WidgetRenderPriorityTrait;

    public $article;

    public function run() {
        $contents = $this->article->articleNavigation;
        $contents = $contents->getText();

        $article = $this->article;

        return $this->render('content_navigation', compact('contents', 'article'));
    }
}