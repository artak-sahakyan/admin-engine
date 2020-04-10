<?php
namespace common\widgets;

use yii\base\Widget;

class ModalWidget extends Widget
{
    public $title;
    public $content;

    public function run() {

        return  $this->render('modal', [
            'title'           => $this->title,
            'content'         => $this->content
        ]);
    }
}
