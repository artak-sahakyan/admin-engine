<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdminGroup */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => ' Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => 'Группы пользователей', 'url' => '/admin/admin-group'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="admin-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
