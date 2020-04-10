<?php
namespace common\widgets;

use yii\base\Widget;

class RegisterMetaWidget extends Widget
{
    public $seo = [];

    public function init()
    {
    	if(!isset($this->seo['title'])) $this->seo['title'] = null;
    	if(!isset($this->seo['keywords'])) $this->seo['keywords'] = null;
    	if(!isset($this->seo['description'])) $this->seo['description'] = null;
    }

    public function run()
    {
        return $this->render('register_meta', ['seo' => $this->seo]);
    }
}