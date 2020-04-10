<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleErrorInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-error-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article_id')->textInput() ?>

    <?= $form->field($model, 'error_in_text')->textarea(['rows' => 6]) ?>
    <?php $model->date_send = (is_numeric($model->date_send)) ? date('Y-m-d', $model->date_send) : null ?>
    <?= $form->field($model, 'date_send')->widget(DatePicker::class, [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ],

    ]);?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
