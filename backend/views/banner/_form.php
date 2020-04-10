<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\widgets\ButtonGroupWidget;
use common\models\{
    BannerType,
    BannerDevice, 
    BannerPartner,
    BannerPlace,
    BannerGroup
};
?>

<div class="banner-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-8">
            <div class="checkbox">
                <?= $form->field($model, 'is_active')->checkBox(['label' => 'Активен', 'selected' => $model->is_active]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'type_id')->dropdownlist(
                    ArrayHelper::map(BannerType::find()->asArray()->all(), 'id', 'name')) 
                ?>
            </div>

            <div class="form-group" id="content">
                <?= $form->field($model, 'content')->textarea(['rows' => 11]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'note')->textarea(['rows' => 11]) ?>
            </div>

        </div>

        <div class="col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($model, 'place_id')->dropdownlist(
                            ArrayHelper::map(BannerPlace::find()->asArray()->all(), 'id', 'name'),
                            [
                                'prompt' => 'Не выбрано',
                            ]) 
                        ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'device_id')->dropdownlist(
                            ArrayHelper::map(BannerDevice::find()->asArray()->all(), 'id', 'name')) 
                        ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'group_id')->dropdownlist(
                            ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name'),
                            [
                            'prompt' => 'Не выбрано',
                            ]) 
                        ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'partners')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(BannerPartner::find()->asArray()->all(), 'id', 'name'),
                                    'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true],
                                    'pluginOptions' => [
                                    'tags' => true,
                                    'tokenSeparators' => [',', ' '],
                                    'maximumInputLength' => 100
                                ],
                            ]);
                        ?>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'service')->dropdownlist(
                            $model->serviceLabel(),
                            [
                            'prompt' => 'Не выбрано',
                            ]
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="checkbox">
                <?= $form->field($model, 'is_scroll_fix')->checkBox(['selected' => $model->is_scroll_fix]) ?>
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

<script type="text/javascript">
    window.onload = function() {
        $("select[name='Banner[type_id]']").on('change',function() {
            container = getContentContainer();
        });
        getContentContainer();
    };

    function getContentContainer() {
        var url = '<?= \yii\helpers\Url::toRoute(['banner/get-content-container']) ?>';
        var type_id = $("select[name='Banner[type_id]']").val();
        var currentContent = $( "#cke_banner-content" ).length ? CKEDITOR.instances['banner-content'].getData() : $("#banner-content").val();

        $.get(
            url, {type_id: type_id, currentContent: currentContent}, function(data) {
            $('#content').html(data);
        });
    }
</script>
