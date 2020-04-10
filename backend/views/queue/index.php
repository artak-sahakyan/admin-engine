<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CmsCronScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Очередь задач';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="cms-cron-schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'pushed_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ]
        ],
    ]); ?>

</div>
