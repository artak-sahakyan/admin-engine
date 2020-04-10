<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleErrorInfo */

$this->title = 'Update Article Error Info: ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Article Error Infos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' =>'Update'];
?>
<div class="article-error-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
