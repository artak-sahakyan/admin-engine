<?php

$this->title = 'Изменить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Баннерные места', 'url' => '/admin/banner-place'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-place-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
