<?php

namespace backend\controllers;

use backend\models\CronLog;
use Yii;
use common\models\ArticleSpellingExcept;


/**
 * ArticleSpellingExceptController implements the CRUD actions for ArticleSpellingExcept model.
 */
class ArticleSpellingExceptController extends AdminController
{
    private $sitePath;
    private $runCommand = 'article-spelling/remove-by-word';

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
        $this->modelClass = ArticleSpellingExcept::class;
        $this->data = ArticleSpellingExcept::getAddedWords();
        $this->data['running'] = CronLog::checkCommandRunningStatus('article-spelling');

    }

    /**
     * Create new except
     * @return page
     */
    public function actionCreateNew()
    {
    	$word = Yii::$app->request->post('word');
    	$count = ArticleSpellingExcept::find()->where(['title' => $word])->count();

    	if($count == 0) {
    		$articleSpellingExcept = new ArticleSpellingExcept([
                'title' => $word
            ]);

    		$articleSpellingExcept->save();
    	}

        exec("php " . $this->sitePath . "/yii article-spelling/update-one " . json_encode($word, JSON_UNESCAPED_UNICODE) ." > /dev/null 2>/dev/null &");
     //   exec("php " . $this->sitePath . "/yii article-spelling/update-one " . json_encode($word, JSON_UNESCAPED_UNICODE) ." > testing1.log");

        return true;
    }

    /**
     * Start update new words
     * @return page
     */
    public function actionUpdateWords()
    {
        self::runCommand();

        \Yii::$app->session->setFlash('warning', "Запущено обновление орфографических ошибок");
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function runCommand()
    {
        exec("php " . $this->sitePath . "/yii $this->runCommand > /dev/null 2>/dev/null &");
    }
}
