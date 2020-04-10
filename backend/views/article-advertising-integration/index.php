<?php

use common\helpers\EditableHelper;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleAdvertisingIntegrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рекламные  интеграции';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-advertising-integration-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <?=\common\widgets\PageSizesCountWidget::widget(['model' => $searchModel]) ?>
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?= \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'columns' => [
                        [
                            'attribute' => 'article_id',
                        ],
                        [
                            'attribute' => 'name',
                        ],
                        [
                            'attribute' => 'end_date',
                            'format' => 'date',
                            'value' => function ($model) {
                                return ($model->end_date) ? date('Y-m-d H:i:s', $model->end_date) : null;
                            },
                        ],
                        [
                            'attribute' => 'shortcode',
                            'filter' => false,
                            'value' => function ($data) {
                                return '[article_advertising_place id="'.$data->id.'"]';
                            },
                        ],
                        [
                            'attribute' => 'is_active',
                            'filter' => false,
                            'format' => 'raw',
                            'filter' => $searchModel->checkboxDataArray,
                            'value' => function($data) use($searchModel){
                                return EditableHelper::checkbox($data, 'is_active', $searchModel->checkboxDataArray);
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn', 'template'=>'{update} {delete}'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>


</div>
