<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\ButtonGroupWidget;
use kartik\select2\Select2;
use common\models\{
    AdminGroup
};
/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= $form->field($model, 'adminGroups')->widget(Select2::class, [
                    'data' => ArrayHelper::map(AdminGroup::find()->asArray()->all(), 'id', 'title'),
                    'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true],
                    'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 100
                ],
            ]);
        ?>
    </div>

    <?= $form->field($model, 'register_date')->textInput() ?>

    <?= $form->field($model, 'last_login_date')->textInput() ?>

    <?= $form->field($model, 'is_active')->textInput() ?>

    <?= $form->field($model, 'settings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'restrict_by_ip')->textInput() ?>

    <?= $form->field($model, 'ips')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-md-12">
            <?= ButtonGroupWidget::widget(['model' => $model]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
