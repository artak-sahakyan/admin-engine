<?php
use common\models\Admin;
use common\helpers\EditableHelper;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Неопубликованные статьи';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-index">
    <div class="box">
 
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <?=\common\widgets\PageSizesCountWidget::widget(['model' => $searchModel, 'action' => 'unpublished']) ?>
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?php $url =  Url::toRoute(['article/get-article-urls-for-copy']) ?>
                <?= \kartik\grid\GridView::widget([
                    'id' => 'article-grid',
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span>",
                    'responsive'=>true,
                    'hover'=>true,
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                        ],
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['style' => 'width:2%'],
                        ],
                        [
                            'attribute' => 'image',
                            'label' => 'Pic',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<img width="50"  src="' . $model->getThumb(50, 50) . '" >';
                            },

                        ],
                        'title' => [
                            'attribute' => 'title',
                            'format' => 'raw',
                            'contentOptions' => ['style' => 'width:250px; white-space: normal;'],
                            'value' => function ($model) {
                                return $model->getLink(true);
                            },
                        ],
                        [
                            'attribute' => 'admin_id',
                            'label' => 'Постер',
                            'filter' => Admin::getEmployeeList(Admin::POSTER),
                            'content' => function($model)  {
                                if(\Yii::$app->user->identity->allowedChangePublisherAndPoster()) {
                                    $res = EditableHelper::dropdown($model, 'admin_id', 'admin.username',  Admin::getEmployeeList(Admin::POSTER));
                                } else {
                                    $res = $model->admin->username ?? '-';
                                }
                                return $res;
                            }
                        ],
                        'category_id' => [
                            'attribute' => 'category_id',
                            'filter' => $searchModel->getAllCategories(),
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'id' => 'category_id'
                            ],
                            'content' => function($model)  use($searchModel){

                                $attribute = 'category_id';
                                $relation = 'category.title';

                                return EditableHelper::dropdown($model, $attribute, $relation, $searchModel->getAllCategories());
                            },

                        ],
                        [
                            'attribute' => 'imported_at',
                            'label' => 'Импортирована',
                            'filter' => false,
                            'value'     => function($model) {
                                return ($model->imported_at) ? date('Y-m-d H:i:s', $model->imported_at) : null;
                            },
                        ],
                        [
                            'attribute' => 'ready_publish_date',
                            'label'     => 'Готовность',
                            'value'     => function($model) {
                                return ($model->ready_publish_date) ? date('Y-m-d', $model->ready_publish_date) : null;
                            },
                            'filter'    => false,

                        ],
                        [
                            'attribute' => 'published_at',
                            'label'     => 'Публикация',
                            'value'     => function($model) {
                                return ($model->published_at) ? date('Y-m-d', $model->published_at) : null;
                            },
                            'filter'    => false,

                        ],
                        [
                            'attribute' => 'publisher_id',
                            'label' => 'Публицист',
                            'filter' => Admin::getEmployeeList(Admin::PUBLISHER),
                            'content' => function($model)  {
                                if(\Yii::$app->user->identity->allowedChangePublisherAndPoster()) {
                                    $res = EditableHelper::dropdown($model, 'publisher_id', 'publisher.username', Admin::getEmployeeList(Admin::PUBLISHER));
                                } else {
                                    $res = isset($model->publisher->username) ? $model->publisher->username : '-';
                                }
                                return $res;
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}{delete}',
                            'header' => 'Управление',
                        ],
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>
