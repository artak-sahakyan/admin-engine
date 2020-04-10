<?php


namespace common\widgets;


use common\helpers\CategoryHelper;
use common\models\ArticleCategory;
use yii\base\Widget;

class MenuWidget extends Widget
{
    public $view = 'menu';
    protected $data;
    public $isArticleRoute = false;
    public $isAmp = false;


    public function init()
    {
        $allCategories = ArticleCategory::find()
            ->asArray()
            ->orderBy('sort')
            ->select(['id', 'title', 'slug', 'parent_id', 'sort'])
            ->all();

        $this->data = CategoryHelper::getCatogiriesWithChilds($allCategories);
    }

    public function run()
    {
        return $this->render($this->view, ['categories' => $this->data, 'isArticleRoute' => $this->isArticleRoute, 'isAmp' => $this->isAmp]);
    }
}