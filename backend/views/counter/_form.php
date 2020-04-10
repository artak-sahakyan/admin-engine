<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Counter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="counter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'turn_on')->checkbox() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="row">
        <div class="col-md-12">
            <?= ButtonGroupWidget::widget(['model' => $model]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
