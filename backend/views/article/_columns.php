<?php
use yii\helpers\Html;
use \kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">
    <?php if($checkboxes = $model->getCheckboxes()): ?>
        <?php $form = ActiveForm::begin([
            'action' => ['change-columns-config'],
            'method' => 'POST',
        ]); ?>

        <?php foreach ($checkboxes as $attribute =>  $is_checked): ?>
        <?php
            $options = ['label'=> $model->getAttributeLabel($attribute)];
            ($is_checked) ? $options['checked'] = '1' : null;
        ?>

        <?=$form->field($model, 'checkboxes[' . $attribute . ']')->checkbox($options); ?>
        <?php endforeach; ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить настройки', ['class' => 'btn btn-primary', 'style' => 'width:100%']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p>Нет настроек для показа</p>
    <?php endif; ?>
</div>
