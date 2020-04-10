<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CronLogMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Command '" . $command . "'";
$this->params['breadcrumbs'][] = ['label' => 'Логи крона', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="cron-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $searchModel->allColumns(),
    ]); ?>

</div>
