<?php


namespace common\widgets;


use common\models\ArticleCategory;
use yii\base\Widget;

class SubCategoryWidget extends Widget
{
    public $category;
    protected $fileName = '_category_subcategories';

    public function run()
    {
        if(!($this->category instanceof ArticleCategory && $this->category)) return '';
        return $this->render($this->fileName, ['category' => $this->category]);
    }
}