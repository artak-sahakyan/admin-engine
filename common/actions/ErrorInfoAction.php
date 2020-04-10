<?php
namespace common\actions;

use Yii;
use yii\base\{ Action, NotSupportedException };
use common\models\{ ArticleErrorInfo, ArticleRating, Article };

class ErrorInfoAction extends Action
{

    public function init()
    {
        parent::init();
        \Yii::$app->controller->enableCsrfValidation = false;
    }

	public function run() {

		$request = Yii::$app->request;

		$article_id = $request->post('id');
        $error_in_text = $request->post('text');

        $textLength = mb_strlen($error_in_text);

        if($textLength > 170) return false;

        $issetInOurDb = ArticleErrorInfo::find()->where(['and', ['article_id' => $article_id], ['like','error_in_text', $error_in_text]])->one();

        if($issetInOurDb) return false;

		return $this->setErrorInfo($article_id, $error_in_text);
	}

    private function setErrorInfo(int $article_id, string $error_in_text) {
    	if(!$article_id) throw new NotSupportedException('Not found article');

    	$articleError = new ArticleErrorInfo();
        $articleError->article_id = $article_id;
        $articleError->error_in_text = $error_in_text;
        $articleError->date_send = time();
        return $articleError->save();
    }

}
