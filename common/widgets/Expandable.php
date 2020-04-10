<?php
namespace common\widgets;

use yii\base\Widget;

class Expandable extends Widget
{
    public $title;
    public $content;
    public $boxTitleStyles;
    public $boxToolsInline;

    public function run() {

        return  $this->render('expandable', [
            'title'           => $this->title,
            'content'         => $this->content,
            'boxTitleStyles'  => $this->boxTitleStyles,
            'boxToolsInline'  => $this->boxToolsInline
        ]);
    }
}
