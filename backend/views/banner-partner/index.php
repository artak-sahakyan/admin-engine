<?php

use common\helpers\EditableHelper;
use common\widgets\PageSizesCountWidget;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Партнерские программы';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="banner-partner-index">
 
    <p>
        <?= Html::a('Создать партнерскую программу', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'responsive'=>true,
                    'hover'=>true,
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'content' => function($data){
                                return EditableHelper::text($data, 'name');
                            },
                        ],
                        [
                            'attribute' => 'alias',
                            'content' => function($data){
                                return EditableHelper::text($data, 'alias');
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}{delete}',],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>
