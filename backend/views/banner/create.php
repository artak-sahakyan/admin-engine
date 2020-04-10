<?php

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => '/admin/banner'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
