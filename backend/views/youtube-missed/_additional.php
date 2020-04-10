<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\FilesHelper;

$buttonName = ($data['running']) ?  'Запущено' : 'Запустить полное обновление';
?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">
            <div class="col-md-4">
                <p>
                    <?php if(empty($updateProgress)): ?>
                    Последнее обновление: <span><?= $data['lastUpdate'] ?></span>
                    <?php else: ?>
                        <?= $updateProgress ?>
                    <?php endif; ?>
                </p>
                <div class="input-group">
                    <?php $form = ActiveForm::begin([
                        'action' => [$data['action']],
                        'method' => 'post',
                    ]); ?>

                    <span class="input-group-btn">
                      <?= Html::button('<i class="fa fa-sync-alt"></i> ' . $buttonName , \common\helpers\ArrayHelper::mergeByCondition($data['running'], ['class' => 'btn btn-primary btn-flat', 'type' => 'submit'], ['disabled' => 'disabled'])) ?>
                    </span>

                    <span class="input-group-btn">
                      <?= Html::button('<i class="fa fa-sync-alt"></i> Перепроверить отсутствующие', \common\helpers\ArrayHelper::mergeByCondition($data['running'], ['class' => 'btn btn-default btn-flat', 'type' => 'submit', 'name' => 'updateMissed', 'value' => 1], ['disabled' => 'disabled'])) ?>
                    </span>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>               
        </div>
    </div>
</div>
