<?php

use common\models\Admin;
use common\models\ArticleCategory;
use common\models\BannerGroup;
use common\models\Expert;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use \kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'category_id')->dropDownList(['' => ''] +ArticleCategory::getRootCategoriesList()) ?>

    <?= $form->field($model, 'banner_group_id')->dropDownList(['' => ''] + BannerGroup::find()->select(['name', 'id'])->indexBy('id')->column())->label('Группа'); ?>

    <?= $form->field($model, 'show_banners')->dropDownList(['' => ''] + $model->getAdsProperties()); ?>

    <?= $form->field($model, 'unique_users_yesterday_count')->label('Визиты'); ?>

    <?= $form->field($model, 'ready_publish_date',[
        'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::class, [
        'name'=>'date_range_1',
        'id' => 'date_range_1',
        'convertFormat'=>true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d',
                'separator' => ' to ',
            ],
            'opens'=>'left'
        ],
        'pluginEvents' => [
            'cancel.daterangepicker' => "function(ev, picker) {\$('#articlesearch-ready_publish_date').val('');$('#article-grid').yiiGridView('applyFilter'); }"
        ],
        'useWithAddon' => false
    ])->label('Готовность'); ?>


    <?= $form->field($model, 'published_at',[
        'addon'=>['prepend'=>['content'=>'<i class="fas fa-calendar-alt"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::class, [
        'name'=>'date_range_2',
        'id' => 'date_range_2',
        'convertFormat'=>true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d',
                'separator' => ' to ',
            ],
            'opens'=>'left'
        ],
        'pluginEvents' => [
            'cancel.daterangepicker' => "function(ev, picker) {\$('#articlesearch-published_at').val('');$('#article-grid').yiiGridView('applyFilter'); }"
        ],
        'useWithAddon' => false
    ])->label('Публикация'); ?>

    <?= $form->field($model, 'admin_id')->dropDownList(['' => ''] + Admin::getEmployeeList(Admin::POSTER))->label('Постер'); ?>

    <?= $form->field($model, 'publisher_id')->dropDownList(['' => ''] + Admin::getEmployeeList(Admin::PUBLISHER))->label('Публицист'); ?>

    <?= $form->field($model, 'expert_id')->dropDownList(['' => ''] + Expert::find()->select(['username', 'id'])->where(['is_expert' => 1])->indexBy('id')->column()); ?>

    <?= $form->field($model, 'pageSize')->hiddenInput(['class' => 'hidden_page_size'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Очистить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
