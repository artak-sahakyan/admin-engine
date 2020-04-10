<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Counter */

$this->title = 'Изменить ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Счетчики', 'url' => '/admin/counter'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="counter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
