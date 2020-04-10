<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
