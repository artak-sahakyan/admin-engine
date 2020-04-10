<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdminGroup */

$this->title = 'Редактировать';
$this->params['breadcrumbs'][] = ['label' => ' Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => 'Группы пользователей', 'url' => '/admin/admin-group'];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => '/admin/admin-group/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="admin-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
