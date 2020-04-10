<?php

use common\helpers\EditableHelper;
use common\widgets\PageSizesCountWidget;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Группы статей';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-group-index">

    <p>
        <?= Html::a('Создать группу статей', ['create'], ['class' => 'btn btn-success']) ?>
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
                    'columns' => [
                        'id',
                        [
                            'attribute' => 'name',
                            'content' => function($data){
                                return EditableHelper::text($data, 'name');
                            },
                        ],
                        [
                            'attribute' => 'show_default_group',
                            'content' => function ($model) {
                                return EditableHelper::checkbox($model, 'show_default_group', [0 => 'Нет', '1' => 'Да']);
                            },
                            'filter'=>array("1"=>"Да","0"=>"Нет"),
                        ],
                        [
                            'label' => 'Кол-во статей',
                            'attribute' => 'articlesCount',
                            'filter' => false
                            
                        ],

                        ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}{delete}',],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>
