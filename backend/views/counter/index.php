<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\helpers\EditableHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CounterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счетчики';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="counter-index">

    <p>
        <?= Html::a('Добавить счетчик', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => false,
                    'columns' => [
                        [
                            'attribute' => 'title',
                            'content' => function($data){
                                return EditableHelper::text($data, 'title');
                            },
                        ],
                        [
                         'attribute' => 'turn_on',
                         'contentOptions' => ['style'=>'text-align:center;width:50px'],
                         'content' => function ($model) {
                            return EditableHelper::checkbox($model, 'turn_on', [0 => 'Нет', '1' => 'Да']);
                        }
                    ],
                    [
                     'attribute' => 'sort',
                     'contentOptions' => ['style'=>'text-align:center;width:50px'],
                     'content' => function($data){
                                return EditableHelper::text($data, 'sort');
                            },
                    ],
                 ['class' => 'yii\grid\ActionColumn', 'template'=>'{update} {delete}'],
             ],
         ]); ?>
     </div>
 </div>
</div>
