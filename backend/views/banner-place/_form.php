<?php

use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;
?>

<div class="banner-place-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
        	<div class="form-group">
	    		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	    	</div>
	    </div>
		<div class="col-md-4">
			<div class="form-group">
				<?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<?= $form->field($model, 'container')->textarea(['rows' => 10, 'placeholder' => 'Используйте [banner] для вставки в код']) ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
		    <?= ButtonGroupWidget::widget(['model' => $model]) ?>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>
