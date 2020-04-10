<?php

$this->title = 'Изменить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы статей', 'url' => '/admin/banner-group'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="banner-group-update">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
