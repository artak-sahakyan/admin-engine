<?php
namespace common\jobs;
 
use common\helpers\YoutubeHelper;
use yii\base\BaseObject;
use common\models\{ 
	ArticleSpelling,
	ArticlePhotoHash,
    Article
};

class ArticleSaveJob extends BaseObject implements \yii\queue\JobInterface
{
	public $article_id;
	public $changedAttributes;
    private $article;

    public function execute($queue)
    {
        $article = Article::find()->where(['id' => $this->article_id])->one();

        $article->sendYandexRecrawl($this->changedAttributes);
        $article->updateStatisticData();
        if ($article->is_published == 1) {
            $article->updateMiratextData();
        }
        ArticleSpelling::checkArticleAndSave($article);
        ArticlePhotoHash::calcHashAndSave($article, ArticlePhotoHash::PREVIEW);
        ArticlePhotoHash::calcHashAndSave($article, ArticlePhotoHash::CONTENT);
        YoutubeHelper::loadYoutube($article);
        $article->validateHtml();
    }
}