<?php


namespace backend\models;

use common\models\NauseaOfArticle;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class Article extends \common\models\Article
{

    public $resetBanners;
    public $countBannerPlaces;
    public $countRelatedBlocks;
    public $countTurboBlocks;
    public $article_id;
    public $doubleBannerPlaces;
    /**
     * before save convert to timestamp
     * @param $model
     * @param $attributes
     * @return mixed
     */
    public function setTimeFormat($attributes)
    {

        foreach ($attributes as $attribute) {
            if(!empty($this->{$attribute})) {
                $this->{$attribute} = strtotime($this->{$attribute});
            }
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function renderRelatedArticles()
    {

        if($this->getRelatedYandexArticles()->count()) {
            $active = $inactive = [];
            $articles = $this->relatedArticles;
            foreach ($articles as $k => $article) {
                $url = '<strong>' . Html::a($article->id, Url::toRoute(['/article/update',  'id' => $article->id]), ['target' => '_blank']) . ', '. $article->getLink() . '</strong>';
                ($k < 3) ? $active[] = $url : $inactive[] = $url;
            }

            return ['active' => $active, 'inactive' => $inactive];
        }
        return null;
    }

}