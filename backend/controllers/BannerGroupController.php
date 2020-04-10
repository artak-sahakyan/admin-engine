<?php
namespace backend\controllers;

use Yii;
use common\models\{ BannerGroup, Article };
use backend\controllers\AdminController;
use backend\events\ControllerModelSaveEvent;

/**
 * BannerGroupController implements the CRUD actions for BannerGroup model.
 */
class BannerGroupController extends AdminController
{
    public function init()
    {
        $this->modelClass = BannerGroup::class;
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'bannerGroupSave']);
    }

    public function bannerGroupSave(ControllerModelSaveEvent $event)
    {
        $article_urls = explode(PHP_EOL, Yii::$app->request->post('articles'));
        
        $ids = $this->getIdsFromUrls($article_urls);
        $ids = (is_array($ids)) ? $ids : [$ids];

        Article::updateAll(['banner_group_id' => $event->model->id], ['in', 'id', $ids]);
    }

    public function actionGetDoubleGroups()
    {
        $article_urls = explode(PHP_EOL, Yii::$app->request->post('urls'));

        $ids = $this->getIdsFromUrls($article_urls);

        $articles = Article::find()
            ->joinWith(['bannerGroup'])
            ->select(['title', 'articles.id', 'articles.banner_group_id'])
            ->where(['in', 'articles.id', $ids])
            ->asArray()
            ->all();

        return json_encode($articles, JSON_UNESCAPED_UNICODE);
    }

    public function actionGetArticlesFromCategory()
    {
        $category_id = Yii::$app->request->post('category_id');

        $articles = Article::find()
            ->where(['category_id' => $category_id])
            ->all();

        $urls = [];

        foreach ($articles as $article) {
            $urls[] = $article->getUrl();
        }

        return json_encode($urls, JSON_UNESCAPED_UNICODE);
    }

    public function actionAcceptArticle()
    {
        $article_id = Yii::$app->request->post('article_id');

        return BannerArticleVsGroup::deleteAll(['article_id' => $article_id]);
    }

    public function actionCancelArticle()
    {
        $article_id = Yii::$app->request->post('article_id');

        $urls = explode(PHP_EOL, Yii::$app->request->post('urls'));
        foreach ($urls as $key => $value) {
            if(stripos($value, '/'.$article_id.'-')) {
                unset($urls[$key]);
            }
        }

        return implode(PHP_EOL, $urls);
    }

    private function getIdsFromUrls(array $urls)
    {
        $ids = [];
        foreach ($urls as $article_url) {
            $url = parse_url($article_url, PHP_URL_PATH);;
            $ids[] = intval(preg_replace('/(\-.*)/','', ltrim($url, '/')));
        }

        return array_filter(array_unique($ids));
    }
}
