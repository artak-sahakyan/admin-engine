<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSpellingExcept */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-spelling-except-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

   <div class="row">
        <div class="col-md-12">
            <?= ButtonGroupWidget::widget(['model' => $model]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
