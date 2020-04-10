<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voting */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => '/admin/voting'];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => '/admin/voting/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="voting-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
