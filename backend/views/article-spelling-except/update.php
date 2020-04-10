<?php

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Орфографические ошибки', 'url' => '/admin/article-spelling'];
$this->params['breadcrumbs'][] = ['label' => 'Слова-исключения', 'url' => '/admin/article-spelling-except'];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => '/admin/article-spelling-except/view?id=' . $model->id];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="article-spelling-except">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
