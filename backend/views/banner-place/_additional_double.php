<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$buttonName = ($running) ?  'Запущено' : 'Запустить полное обновление';

?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">
            <div class="col-md-4">
                
                <div class="input-group">
                    <span class="input-group-btn">
                      <?= Html::a('<i class="fa fa-chevron-left"></i> Потерянные места', ['/banner-place/lost-places'] , ['class' => 'btn btn-default btn-flat']) ?>
                    </span>

                    <?php $form = ActiveForm::begin([
                        'action' => [$action],
                        'method' => 'post',
                    ]); ?>

                    <span class="input-group-btn">
                      <?= Html::button('<i class="fa fa-sync-alt"></i> ' . $buttonName, \common\helpers\ArrayHelper::mergeByCondition($running, ['class' => 'btn btn-primary btn-flat', 'type' => 'submit'], ['disabled' => 'disabled'])) ?>
                    </span>

                    <?php ActiveForm::end(); ?>
                </div>

                <div>Последнее полное обновление <?= $lastRun ?></div>

            </div>               
        </div>
    </div>
</div>