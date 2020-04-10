<?php
namespace common\widgets;

use Yii;
use common\helpers\FilesHelper;
use common\models\Article;
use backend\models\CommonData;
use yii\base\Widget;
use yii\db\Expression;
use common\traits\WidgetRenderPriorityTrait;

class PopularArticlesWidget extends Widget
{
    use WidgetRenderPriorityTrait;

    /**
     * Send to template vars from this object
     * @var array
     */
    public $renderParams = [
        'articles'          => '',
        'places'            => '',
    ];
    public $limit;
    public $places;
    public $view = '_popular';
    private $articles = [];
    
    public function init()
    {
        parent::init();

        $this->limit = Yii::$app->params['articles']['popular_limit'];
    }

    public function run() {
        self::getPopularArticles();

        // set render params
        $renderParams = [];
        foreach ($this->renderParams as $key => $value) {
            $renderParams[$key] = $this->{$key};
        }

        echo $this->render($this->view, $renderParams);
    }

    private function getPopularArticles()
    {
        $moscowTime =  time() + 60 * 60 * 3;

        $count_articles = Article::find()->where(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', $moscowTime]])->count();

        $ids = $this->generateRandomArray($count_articles);

        $fixArticle = Article::find()->where(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', $moscowTime], ['is_fix_sidebar' => true]])->one();

        $activeQuery = Article::find()->where(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', $moscowTime], ['in', 'id', $ids]]);

        if(empty($fixArticle)) {
            $this->articles = $activeQuery->limit(6)->all();
        } else {
            $this->articles = array_merge([$fixArticle], $activeQuery->limit(5)->all());
        }
     
        return $this->articles;
    }

    private function generateRandomArray($max)
    {     
        $random_number_array = range(1, $max);
        shuffle($random_number_array );
        $random_number_array = array_slice($random_number_array, 0, 30);
        return $random_number_array;
    }
}
