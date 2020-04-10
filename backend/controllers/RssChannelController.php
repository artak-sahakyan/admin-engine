<?php

namespace backend\controllers;

use Yii;
use common\models\{ 
    RssChannel, 
    Article 
};
use backend\models\RssChannelSearch;
use backend\controllers\AdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\RssHelper;

/**
 * RssChannelController implements the CRUD actions for RssChannel model.
 */
class RssChannelController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGenerateRss(int $id)
    {
        $model = $this->findModel($id);
        
        $pages = RssHelper::generate($model);

        if($pages) {
            \Yii::$app->session->setFlash('success', "Будет сгенерировано ".$pages." файлов");
        } else {
            \Yii::$app->session->setFlash('danger', "Ошибка создания файлов");
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all RssChannel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RssChannelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RssChannel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RssChannel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RssChannel();
        $input = self::serializeFilter(Yii::$app->request->post(), $model->formName());

        if ($model->load($input) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->unserializeFilter();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RssChannel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $input = self::serializeFilter(Yii::$app->request->post(), $model->formName());

        if ($model->load($input) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->unserializeFilter();

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Serialize filter params to RssChannel.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param array $input
     * @param string $className
     * @return array
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function serializeFilter(array $input, string $className)
    {
        if($input && $input[$className]) {
            $filter = [
                'is_published'      => $input[$className]['is_published'],
                'is_turbopage'      => $input[$className]['is_turbopage'],
                'send_zen'          => $input[$className]['send_zen'],
                'articleCategories' => $input[$className]['articleCategories'],
                'bannerGroups'      => $input[$className]['bannerGroups'],
            ];

            $input[$className]['filter'] = serialize($filter);
        }

        return $input;
    }

    /**
     * Deletes an existing RssChannel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RssChannel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RssChannel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RssChannel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
