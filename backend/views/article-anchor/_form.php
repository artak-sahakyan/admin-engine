<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleAnchor */
/* @var $form yii\widgets\ActiveForm */

$preffix = ($model->isNewRecord) ? 'add' : 'update';

?>

<div class="article-anchor-form">

    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" align="center"><strong>Введите имя и ключ яндекс профиля</strong></h5>
    </div>
    <div class="modal-body">
        <?php $form = ActiveForm::begin(['id' => 'anchor-form']); ?>

        <?php if($model->isNewRecord) : ?>
            <?= $form->field($model, 'article_id')->hiddenInput(['class' => 'hidden-anchor-article-id'])->label(false) ?>
        <?php endif; ?>


        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'wordstat_count')->textInput() ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрить</button>
        <button type="button" class="btn btn-primary <?=$preffix?>-new-anchor">Запустить</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>