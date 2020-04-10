<?php

use common\helpers\EditableHelper;
use common\widgets\PageSizesCountWidget;
use yii\helpers\Html;

$this->title = 'Баннерные места';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="banner-place-index">

    <p>
        <?= Html::a('Создать баннерное место', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                    'responsive'=>true,
                    'hover'=>true,
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'id',
                        [
                            'attribute' => 'alias',
                            'content' => function($data){
                                return EditableHelper::text($data, 'alias');
                            },
                        ],
                        [
                            'attribute' => 'name',
                            'content' => function($data){
                                return EditableHelper::text($data, 'name');
                            },
                        ],
                        [
                            'label' => 'Кол-во баннеров',
                            'value' => function($model) {
                                return $model->bannersCount;
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}{delete}',],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
