<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Expert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthdate')->textInput() ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'think')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'married')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_expert')->textInput() ?>

    <div class="row">
        <div class="col-md-12">
            <?= ButtonGroupWidget::widget(['model' => $model]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
