<?php

use common\widgets\PageSizesCountWidget;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticlePhotoHashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $data['title'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-photo-hash-index">

   <?= $this->render('_additional', ['action' => $data['action'], 'running' => $data['running']]); ?>

    <div class="box">
        <div class="box-header">
            <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?= \kartik\grid\GridView::widget([
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'image',
                            'label' => 'Изображение',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                if(isset($model->article)){
                                    return '<img width="50"  src="' . $model->article->getThumb(50, 50) . '" >';
                                }
                            },

                        ],
                        [
                            'attribute' => 'path',
                            'contentOptions' => ['style' => 'max-width:100%; white-space: normal;'],
                        ],
                        [
                            'attribute' => 'article_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if(isset($model->article)){
                                    return $model->article->getLink(true);
                                }
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>




</div>
