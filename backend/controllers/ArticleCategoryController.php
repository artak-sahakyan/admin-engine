<?php
namespace backend\controllers;

use backend\models\ArticleCategorySearch;
use Yii;
use common\models\ArticleCategory;

/**
 * ArticleCategoryController implements the CRUD actions for ArticleCategory model.
 */
class ArticleCategoryController extends AdminController
{
    public function init()
    {
        $this->modelClass = ArticleCategory::class;
    }

    public function actionIndex()
    {
        $this->params['parent_id'] = '0';

        // ajax query
        $parentId = Yii::$app->request->get('parent_id');
        if (!empty($parentId)) {
            $this->params['parent_id'] = $parentId;
            $this->page = '_index_row.php';

            $searchModel = new ArticleCategorySearch();
            $searchModel->pageSize = null;

            $params = $this->params;
            if($params && is_array($params)) {
                foreach ($params as $attribute => $value) {
                    $searchModel->{$attribute} = $value;
                }
            }

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->sort = false;

            return $this->renderPartial((!$this->page) ? 'index' : $this->page, [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return parent::actionIndex();
    }
}
