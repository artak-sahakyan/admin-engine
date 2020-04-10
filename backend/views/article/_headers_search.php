<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\BannerType;
?>

<div class="banner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['headers'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'article_id'); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'chapters')->textInput(['value' => $limits['chapters']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'h1')->textInput(['value' => $limits['h1']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'title')->textInput(['value' => $limits['title']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'description')->textInput(['value' => $limits['description']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'keywords')->textInput(['value' => $limits['keywords']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'alt')->textInput(['value' => $limits['alt']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'text')->textInput(['value' => $limits['text']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'baden_points')->textInput(['value' => $limits['baden_points']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'bigram')->textInput(['value' => $limits['bigram']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'trigram')->textInput(['value' => $limits['trigram']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'word_density')->textInput(['value' => $limits['word_density']]); ?>
        </div>
        <div class="col-xs-2">
            <?= $form->field($model, 'miratext_water')->textInput(['value' => $limits['miratext_water']]); ?>
        </div> 
    </div>

    <div class="form-group">
        <?= Html::button('<i class="fa fa-fw fa-filter"></i> Отфильтровать', ['class' => 'btn btn-primary', 'type' => 'submit', 'title' => 'Отфильтровать объекты']) ?>
        <!-- <?= Html::button('<i class="fa fa-fw fa-download"></i> Экспорт', ['class' => 'btn btn-success', 'type' => 'submit', 'title' => 'Экспорт']) ?> -->
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
