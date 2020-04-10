<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$buttonName = ($running) ?  'Запущено' : 'Запустить обновление';
?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">
            <div class="col-md-4">
                <div class="input-group">
                    <?php $form = ActiveForm::begin([
                        'action' => [$action],
                        'method' => 'post',
                    ]);
                    ?>

                    <span class="input-group-btn">
                      <?= Html::button('<i class="fa fa-sync-alt"></i>' . $buttonName , \common\helpers\ArrayHelper::mergeByCondition($running, ['class' => 'btn btn-primary btn-flat', 'type' => 'submit'], ['disabled' => 'disabled'])) ?>
                    </span>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>               
        </div>
    </div>
</div>
