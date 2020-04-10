<?php

use common\models\ArticleCategory;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\ButtonGroupWidget;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-category-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header" style="cursor: move;">
                  <h3 class="box-title">Основное</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($model, 'parent_id')->dropDownList(['' => 'Не выбрана'] + ArticleCategory::getRootCategoriesList()) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header" style="cursor: move;">
                  <h3 class="box-title">Мета</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($model, 'h1Title')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'metaDescription')->textarea(['rows' => 3, 'maxlength' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'metaKeywords')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'head_text')->textarea(['rows' => 3]) ?>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header" style="cursor: move;">
                  <h3 class="box-title">Дополнительно</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'sort') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'is_medical')->checkbox(); ?>
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
