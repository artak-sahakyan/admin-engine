<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => '/admin/admin-user/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
