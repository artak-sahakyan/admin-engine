<?php
namespace common\widgets;

use common\models\Article;
use common\models\ArticleRelatedYandex;
use yii\base\Widget;
use yii\db\Expression;
use common\traits\WidgetRenderPriorityTrait;

/**
 * Виджет, выводящий блок, который содержит в себе похожие статьи для заданной
 */
class ArticleWidget extends Widget
{
    use WidgetRenderPriorityTrait;

    /**
     * @var Исключенные id статей
     */
    public static $excludesIds = [];
    /**
     * @var Статья, для которой собирается перелинковка
     */
    public $article;
    /**
     * @var Максимальное кол-во статей в блоке
     */
    public $block_limit;
    /**
     * @var Шаблон для вывода, по умолчанию из engine
     */
    public $template = 'article_related';
    /**
     * @var Заголовок
     */
    public $title;
    /**
     * @var string сортировка выборки relatedArticles
     */
    public $relatedOrder = '';
    /**
     * @var Статьи из перелинковки
     */
    public static $relatedArticles;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if(empty(static::$excludesIds)) {
            static::$excludesIds[] = $this->article->id;
        }

        if(empty(self::$relatedArticles)) {
            $moscowTime =  time() + 60 * 60 * 3;
            
            $count_articles = Article::find()->where(['AND',['is_published' => 1], ['is_actual' => 1], ['<', 'published_at', $moscowTime]])->count();

            self::$relatedArticles = $this->generateRandomArray($count_articles);
        }
    }

    public function run() 
    {
        $articles = [];

        if (self::$relatedArticles) {
            $articles = Article::find()
                ->select(['id', 'title', 'slug', 'main_query', 'image_color', 'image_extension', 'published_at',])
                ->where(['IN', 'id', self::$relatedArticles])
                ->andWhere(['NOT IN', 'id', static::$excludesIds])
                //->andWhere(['is_actual' => 1])
                ->published()
                ->limit($this->block_limit)
                ->orderBy(new Expression('FIELD(id, ' . implode(',', self::$relatedArticles) .')'))
                ->all();

            foreach ($articles as $article) {
                static::$excludesIds[] = $article->id;
            }

            $countArticle = count($articles);
            if($countArticle != $this->block_limit) {
                $neededCount = $this->block_limit - $countArticle;
                $byCategoryArticles = Article::find()
                    ->select(['id', 'title', 'slug', 'image_color'])
                    ->where(['category_id' => $this->article->category_id])
                    ->andWhere(['NOT IN', 'id', static::$excludesIds])
                    //->andWhere(['is_actual' => 1])
                    ->published()
                    ->limit($neededCount)
                    ->all();

                if($byCategoryArticles) {
                   $articles = array_merge($articles, $byCategoryArticles);
                }
            }
        }

        return $this->render($this->template, [ 'articles' => $articles, 'title' => $this->title ]);
    }

    /**
     * @return array
     */
    private function getRelatedArticles()
    {
        $query = ArticleRelatedYandex::find()
            ->select('related_article_id')
            ->where(['article_id' => $this->article->id]);

        if ($this->relatedOrder) {
            $query = $query->orderBy($this->relatedOrder);
        }

        return $query->column();
    }

    private function generateRandomArray($max)
    {     
        $random_number_array = range(1, $max);
        shuffle($random_number_array );
        $random_number_array = array_slice($random_number_array, 0, 100);
        return $random_number_array;
    }
}
