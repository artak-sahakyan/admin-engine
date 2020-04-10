<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CronLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cron log';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="cron-log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $searchModel->allCollumns(),
    ]); ?>


</div>
