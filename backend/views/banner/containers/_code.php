<?php

use yii\widgets\ActiveForm;

$form = ActiveForm::begin();

echo $form->field($model, 'content')->textarea(['rows' => 11]);
