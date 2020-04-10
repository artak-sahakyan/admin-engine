<?php

namespace backend\controllers;

use common\helpers\ByTextHelper;
use Yii;
use common\models\ArticleAnchor;

/**
 * ArticleAnchorController implements the CRUD actions for ArticleAnchor model.
 */
class ArticleAnchorController extends AdminController
{
    public function init()
    {
        $this->modelClass = ArticleAnchor::class;
    }


    public function actionCreate()
    {
        $model = new ArticleAnchor();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 1;
        }

        return $this->renderAjax('_form', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = ArticleAnchor::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 1;
        }

        return $this->renderAjax('_form', compact('model'));
    }

    public function actionDelete($id)
    {
        if($model = ArticleAnchor::findOne($id)) {
            $model->delete();
            return 1;
        }
        return 0;
    }

    public function actionByTextGetAnchors($id = null)
    {
        $anchors = ByTextHelper::anchors($id);

        if(!is_array($anchors)) {
            return $anchors;
        } else {

            ArticleAnchor::deleteAll(['article_id' => $id]);

            foreach($anchors as $title => $wordstat) {
                $model = new ArticleAnchor();
                $model->article_id = $id;
                $model->title = $title;
                $model->wordstat_count = $wordstat;
                $model->save();
            }

            return 'Сохранено';
        }

    }

}
