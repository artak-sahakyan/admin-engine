<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Expert */

$this->title = 'Обновить ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Эксперты', 'url' => '/admin/expert'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="expert-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
