<?php

$this->title = 'Изменить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Партнерские программы', 'url' => '/admin/banner-partner'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-partner-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
