<?php
namespace common\widgets;

use yii\base\InvalidConfigException;
use yii\widgets\Breadcrumbs;

class BreadCrumbsWidget extends Breadcrumbs
{
    use \common\traits\WidgetRenderPriorityTrait;

    public function init()
    {
        parent::init();

        $this->links = array_filter($this->links);
        if (empty($this->links)) {
            throw new InvalidConfigException('Empty links');
        }
    }

    public function run()
    {
        self::setHomeLink();
        echo $this->render('_breadcrumbs', array('links' => $this->links));
    }

    private function setHomeLink()
    {
        $homeLink = ['label' => 'Главная', 'url' => '/'];
        return array_unshift($this->links, $homeLink);
    }
}
