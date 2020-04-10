<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ErrorLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ошибки на сайте';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="error-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить все ошибки', ['delete-all'], ['class' => 'btn btn-danger']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         //   'id',
            'level',
            'category',
            'log_time:datetime',
          //  'prefix:ntext',
            [
                'attribute' => 'message',
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center', 'width' => '20%'],

                'value' => function($model) {
                    return Html::textarea('message', $model->message,['rows' => 10, 'cols'=> 80]);
                }
            ],
          //  'message:ntext',
            'url:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>


</div>
