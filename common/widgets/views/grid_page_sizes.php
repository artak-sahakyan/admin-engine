<?php
use yii\helpers\Html;
use \kartik\form\ActiveForm;

/* @var $form yii\widgets\ActiveForm */
?>


<div class="dataTables_length" id="page_size_length">

    <?php $form = ActiveForm::begin([
        'id' => 'page_size_form',
        'action' => [$action],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pageSize')->dropDownList($sizes, ['prompt'=>'Кол-во элементов'])->label(false) ?>
    <?php ActiveForm::end(); ?>

</div>
