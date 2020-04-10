<?php
namespace common\actions;

use yii\base\Action;

use Yii;
use yii\base\NotSupportedException;
use common\models\{ ArticleRating, Article };

class RatingAction extends Action
{
	/**
     * @var Запрос
     */
	private $request;
	/**
     * @var Рейтинг
     */
	private $articleRating;

	public function run($action) {
		$this->request 	= Yii::$app->request;
		$article_id = $this->request->get('article_id');
        
		$this->articleRating = $this->getArticleRating($article_id);
        $this->$action();
	}

	/**
     * @return json
     */
    public function like() {
    	$this->articleRating->addPositive();
    	echo json_encode($this->articleRating->getAttributes(array('positive','negative')));
    }

    /**
     * @return json
     */
    public function dislike() {
    	$this->articleRating->addNegative($this->request->get('comment'));
    	echo json_encode($this->articleRating->getAttributes(array('positive','negative')));
    }

    /**
     * @return ArticleRating|null
     */
    private function getArticleRating(int $article_id) {
    	if(!$article_id) {
    		throw new NotSupportedException('Not found article');
    	}
    
		$articleRating = ArticleRating::find()->where(['article_id' => $article_id])->one();
    	if(!$articleRating) {
    		$articleRating = new ArticleRating();
    		$articleRating->article_id = $article_id;
    		$articleRating->save();
    	}

    	return $articleRating;
    }
}
