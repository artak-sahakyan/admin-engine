<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CmsCronSchedule */

$this->title = 'Изменить' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Расписание задач крона', 'url' => '/admin/cron'];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => '/admin/cron/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="cms-cron-schedule-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
