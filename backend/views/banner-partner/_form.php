<?php

use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;
?>

<div class="banner-partner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= ButtonGroupWidget::widget(['model' => $model]) ?>

    <?php ActiveForm::end(); ?>

</div>
