<?php

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Группы статей', 'url' => '/admin/banner-group'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-group-create">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
