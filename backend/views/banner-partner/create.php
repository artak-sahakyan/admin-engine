<?php

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Партнерские программы', 'url' => '/admin/banner-partner'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-partner-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
