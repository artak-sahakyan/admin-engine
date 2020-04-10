<?php

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Баннерные места', 'url' => '/admin/banner-place'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-place-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
