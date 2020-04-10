<?php

$this->title = 'Обновить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => '/admin/banner'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
