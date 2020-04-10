<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CmsCronSchedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-cron-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'schedule')->textInput(['maxlength' => true, 'placeholder' => '* * * * *', 'width' => 500]) ?>
            <?php $options = ($model->id) ? $model->options : $model->consoleCommandsFiles ?>
            <?= $form->field($model, 'command')->dropDownList($options,['maxlength' => true]) ?>

            <?= $form->field($model, 'params')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

            <?= $form->field($model, 'is_active')->checkbox() ?>


            <div class="form-group">
                <?= \common\widgets\ButtonGroupWidget::widget(['model' => $model]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>


    <div>
        <img src="http://ts-soft.ru/blog/wp-content/uploads/2013/12/pngbase6494d01710e62a4eee.png"/>
    </div>
</div>
