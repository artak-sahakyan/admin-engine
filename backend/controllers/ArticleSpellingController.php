<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\CronLog;
use common\helpers\FilesHelper;
use Yii;
use common\models\ArticleSpelling;
use backend\controllers\AdminController;

/**
 * ArticleSpellingController implements the CRUD actions for ArticleSpelling model.
 */
class ArticleSpellingController extends AdminController
{
    private $sitePath;
    private $runCommand = 'article-spelling';

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
        $this->modelClass = ArticleSpelling::class;
    }

    public function actionIndex()
    {
        return parent::actionIndex();
    }

    public function actionSpellingPreview()
    {
        $this->data['action']  = 'update-list';

        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommand])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $this->data['running'] = $cronLog['status'] == 'running';
        $this->data['cronId'] = $cronLog['id'];

        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommand, 'status' => 'done'])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $this->data['lastRun'] = !empty($cronLog['created_at']) ? date('Y-m-d H:i:s', $cronLog['created_at']) : '-';

        return $this->actionIndex();
    }

    /**
     * Start update spelling everyone articles
     * @return page
     */
    public function actionUpdateList()
    {
        self::runCommand();
        \Yii::$app->session->setFlash('warning', "Запущено полное обновление орфографических ошибок");
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Start update the article
     * @return \yii\web\Response
     */
    public function actionUpdateOne()
    {
        $articleId = Yii::$app->request->get('article_id');
        $article = Article::findOne($articleId);
        ArticleSpelling::checkArticleAndSave($article);

        return $this->redirect(Yii::$app->request->referrer);
    }

    private function runCommand()
    {
        exec("php " . $this->sitePath . "/yii $this->runCommand > /dev/null 2>/dev/null &");

        return $this;
    }

}
