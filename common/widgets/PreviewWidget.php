<?php
namespace common\widgets;

use yii\base\{
    InvalidConfigException,
    Widget
};

class PreviewWidget extends Widget
{
    use \common\traits\WidgetRenderPriorityTrait;

    public $renderFile = '_items';
    /**
     * Send to template vars from this object
     * @var array
     */
    public $renderParams = [
        'parentDivClass'    => '',
        'itemsMainDivClass' => '',
        'mobile'            => '',
        'articles'          => '',
    ];
    public $parentDivClass = '';
    public $itemsMainDivClass = false;
    public $mobile = [];
    public $offset = 0;
    public $limit = 4;
    public $articles;

    public function init()
    {
        parent::init();

        if ($this->articles === null) {
            throw new InvalidConfigException('Articles not set');
        }
    }

    public function run()
    {
        if(self::prepareArticles())
        {
            // set render params
            $renderParams = [];
            foreach ($this->renderParams as $key => $value) {
                $renderParams[$key] = $this->{$key};
            }

            echo $this->render($this->renderFile, $renderParams);
        }
    }

    protected function prepareArticles()
    {
        $this->articles = array_slice($this->articles, $this->offset,$this->limit);
        return $this->articles;
    }
}
