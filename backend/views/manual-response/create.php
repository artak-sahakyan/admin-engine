<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ManualResponse */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Ответы сервера', 'url' => '/admin/manual-response'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="manual-response-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
