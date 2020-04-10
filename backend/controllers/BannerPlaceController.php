<?php
namespace backend\controllers;

use backend\models\CommonData;
use backend\models\CronLog;
use common\models\Article;
use Yii;
use common\models\BannerPlace;
use backend\controllers\AdminController;
use backend\models\ArticleSearch;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * BannerPlaceController implements the CRUD actions for BannerPlace model.
 */
class BannerPlaceController extends AdminController
{
    private $sitePath;
    private $runCommand = 'article-double-banner-place';

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
        $this->modelClass = BannerPlace::class;
    }

    public function actionLostPlaces()
    {
    	$searchModel = new ArticleSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->sort = ['defaultOrder' => []];
        $dataProvider->query = $dataProvider->query->addSelect(
            ['*,
            articles.id as article_id, 
            ((char_length(articles.content) - char_length(replace(articles.content,\'[banner\',\'\'))) div char_length(\'[banner\')) as countBannerPlaces, 
            ((char_length(articles.content) - char_length(replace(articles.content,\'_related\',\'\'))) div char_length(\'_related\')) as countRelatedBlocks,
            ((char_length(articles.content) - char_length(replace(articles.content,\'turbo\',\'\'))) div char_length(\'turbo\')) as countTurboBlocks'
            ]);

        $dataProvider->sort->attributes['countBannerPlaces'] = [
            'asc' => ['countBannerPlaces' => SORT_ASC],
            'desc' => ['countBannerPlaces' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['countRelatedBlocks'] = [
            'asc' => ['countRelatedBlocks' => SORT_ASC],
            'desc' => ['countRelatedBlocks' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['countTurboBlocks'] = [
            'asc' => ['countTurboBlocks' => SORT_ASC],
            'desc' => ['countTurboBlocks' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['article_id'] = [
            'asc' => ['articles.id' => SORT_ASC],
            'desc' => ['articles.id' => SORT_DESC],
        ];


        return $this->render('lost-places', compact('searchModel', 'dataProvider'));
    }

    public function actionDoubleBannerPlaces()
    {
        $articleIds = [];
        $articleIds = CommonData::value('article-double-banner-place_ids');

        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => []];
        $dataProvider->query->andWhere(['articles.id' => $articleIds]);

        // last run
        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommand, 'status' => 'done'])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $lastRun = !empty($cronLog['created_at']) ? date('Y-m-d H:i:s', $cronLog['created_at']) : '-';

        // It is running
        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommand])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $running = $cronLog['status'] == 'running';

        $action = 'update-list';

        return $this->render('double-banner-places', compact(
    'searchModel',
         'dataProvider',
            'lastRun',
            'running',
            'action'
        ));
    }

    public function actionUpdateList()
    {
        exec("php " . $this->sitePath . "/yii $this->runCommand > /dev/null 2>/dev/null &");

        sleep(3);
        \Yii::$app->session->setFlash('warning', "Запущено полное обновление сбора дублированных мест");
        return $this->redirect(Yii::$app->request->referrer);
    }


}
