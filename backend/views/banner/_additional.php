<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$options = [
        ['actionName' => 'turnOn',      'title' => 'Включить'],
        ['actionName' => 'turnOff',     'title' => 'Выключить'],
        ['actionName' => 'copy',        'title' => 'Скопировать'],
        ['actionName' => 'setParnter',  'title' => 'Задать партнерку',      'ajaxGetList' => 'massGetPartners'],
        ['actionName' => 'setGroup',    'title' => 'Задать группу статей',  'ajaxGetList' => 'massGetGroups'],
        ['actionName' => 'deleteAll',   'title' => 'Удалить']
    ];       
?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>

            <div class="col-xs-3">    
                <div class="input-group input-group">
                    <?= Html::activeTextInput($model, 'id', ['maxlength' => 5, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск по ID']) ?>
                    <span class="input-group-btn">
                      <?= Html::submitButton('Найти', ['class' => 'btn btn-default btn-flat']) ?>
                    </span>
                </div>
            </div>

            <div class="col-xs-5">    
                <div class="input-group input-group">
                    <?= Html::activeTextInput($model, 'content', ['maxlength' => 50, 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Поиск по содержанию']) ?>
                    <span class="input-group-btn">
                      <?= Html::submitButton('Найти', ['class' => 'btn btn-default btn-flat']) ?>
                    </span>
                </div>
            </div>

            <?php ActiveForm::end(); ?>    

            <?php $form = ActiveForm::begin([
                'action' => ['additional'],
                'method' => 'get',
            ]); ?>

            <div class="col-xs-4">    
                <div class="input-group">

                    <?= HTML::dropDownList('massActionForm', null, ArrayHelper::map($options, 'actionName', 'title'), [
                        'class'     => 'form-control',
                        'prompt'    => 'Массовые действия (не выбрано)',
                        'onchange'  => 'js:updateAdditionalDropDown()',
                        'id'        => 'massActionForm',
                        'options'   => ArrayHelper::index($options, 'actionName')
                    ]) ?>

                    <?= HTML::dropDownList('additionalDropDown', null, [], [
                        'class'     => 'form-control',
                        'style'     => 'display:none',
                        'id'        => 'additionalDropDown'
                    ]) ?>

                    <span class="input-group-btn">
                      <?= Html::button('Применить', ['class' => 'btn btn-default btn-flat', 'onclick' => 'js:submitAdditionalForm();']) ?>
                    </span>
                </div>
            </div>

            <?php ActiveForm::end(); ?>     
        </div>
    </div>
</div>

<script type="text/javascript">
    var massActionFormId    = '#w2';
    var mainTableId         = '#w3';

    window.onload = function() {
        massActionEnabled(true);
        $checkboxes = $(mainTableId + ' :checkbox');

        $checkboxes.change(function() {
            massActionEnabled(true);
            $checkboxes.each(function( index ) {
                if ($(this).prop('checked') == true){
                    return massActionEnabled(false);
                }
            });
        });
    };

    function massActionEnabled(enabled) {
        $(massActionFormId).find('button, select').attr("disabled", enabled);
    }

    function submitAdditionalForm() {
        var keys = $(mainTableId).yiiGridView('getSelectedRows');

        if(keys.length > 0) {
            var input = $("<input>").attr("type", "hidden").attr("name", "selectedBanners").val(keys);
            $(massActionFormId).append(input);
            $(massActionFormId).submit();
        }        
    }

    function updateAdditionalDropDown() {
        var url                 = '<?= \yii\helpers\Url::toRoute(['banner/ajax-get-list']) ?>';
        var ajaxGetList         = $('#massActionForm option:selected').attr('ajaxGetList');
        var additionalDropDown  = $('#additionalDropDown');

        additionalDropDown.find('option').remove().end();
        additionalDropDown.hide();

        if(ajaxGetList) {
            $.get(url + "?ajaxGetList=" + ajaxGetList, function(data) {
                var additionalDropDown = $('#additionalDropDown');
                additionalDropDown.show();
                var data = jQuery.parseJSON(data);

                $.each(data,function(key, value) {
                    additionalDropDown.append('<option value="' + key + '">' + value + '</option>');
                });
            });
        }
    }
</script>
