<?php

use common\widgets\PageSizesCountWidget;
use yii\grid\GridView;
use yii\helpers\{ Html, Url };

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleYoutubeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проверка Youtube видео';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="youtube-missed-index">

    <?= $this->render('_additional', ['data' => $data]); ?>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <?=PageSizesCountWidget::widget(['model' => $searchModel]) ?>
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?= \kartik\grid\GridView::widget([
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'id',
                        [
                            'attribute' => 'Название статьи',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->article->getLink(true);
                            },
                        ],
                        [
                            'attribute' => 'missed_position',
                            'value' => function ($model) {
                                return $model->missed_position + 1;
                            },
                        ],
                        'link',
                        [
                            'attribute' => 'missed_updated_at',
                            'format' => 'date',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{edit}{update-single}',
                            'buttons'=>[
                                'edit'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-pencil' style='margin-right: 10px' aria-hidden='true'></span>",Url::toRoute('/article/update?id=' . $model->article_id), ['id' => 'update-article', 'title' => 'Редактировать статью']);
                                },
                                'update-single'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-refresh' style='margin-right: 10px' aria-hidden='true'></span>",Url::toRoute('/youtube-missed/update-single?id=' . $model->article_id), ['id' => 'update-single', 'title' => 'Обновить']);
                                },
                            ]
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>




</div>
