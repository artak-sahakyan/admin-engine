<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Voting */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => '/admin/voting'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="voting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
