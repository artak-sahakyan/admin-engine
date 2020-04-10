<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\BannerType;

?>

<div class="banner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'place_id')->dropdownlist(
                $placesDataArray,
                ['prompt' => 'Не выбрано'])
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'device_id')->dropdownlist(
                $devicesDataArray,
                ['prompt' => 'Не выбрано'])
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'group_id')->dropdownlist(
                $groupsDataArray,
                ['prompt' => 'Все группы'])
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'type_id')->dropdownlist(
                ArrayHelper::map(BannerType::find()->asArray()->all(), 'id', 'name'),
                ['prompt' => 'Не выбрано'])
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'partners')->dropdownlist(
                $partnersDataArray,
                ['prompt' => 'Не выбрано']) 
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'service')
                ->label('Сервис', ['title' => $model->getAttributeLabel('service')])
                ->dropdownlist(
                    $model->serviceLabel(),
                    ['prompt' => 'Не выбрано']
                )
            ?>
        </div>

        <div class="col-xs-2">
            <?= $form->field($model, 'is_active')->dropdownlist(
                array("1"=>"Включен","0"=>"Выключен"),
                ['prompt' => 'Не выбрано']) 
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::button('<i class="fa fa-fw fa-filter"></i> Отфильтровать', ['class' => 'btn btn-primary', 'type' => 'submit', 'title' => 'Отфильтровать объекты']) ?>
        <?= Html::resetButton('Сброс', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
