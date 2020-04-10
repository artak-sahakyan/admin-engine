<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleAdvertisingIntegration */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-advertising-integration-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-10">
                        <div class="checkbox">
                            <?= $form->field($model, 'is_active')->checkbox() ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $form->field($model, 'article_id')->textInput() ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php $model->end_date = (!$model->end_date) ? date('Y-m-d') : date('Y-m-d', $model->end_date); ?>
                            <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy-mm-dd'
                                ],

                            ]);?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                         <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
                     </div>
                 </div>
             </div>
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
