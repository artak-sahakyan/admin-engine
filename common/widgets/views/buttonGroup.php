<?php

use yii\helpers\Html;

?>

<?= Html::a('<i class="fa fa-fw fa-close"></i> Отмена', Yii::$app->request->referrer, ['class' => 'btn btn-default', 'title' => 'Отменить и вернуться назад']) ?>

<?php if($model->id): ?>

<?= Html::button('<i class="fa fa-fw fa-save"></i> Применить', ['class' => 'btn btn-primary', 'name' => 'apply', 'value' => 'true', 'type' => 'submit', 'title' => 'Применить изменения']) ?>

<?= Html::button('<i class="fa fa-fw fa-chevron-down"></i> Сохранить', ['class' => 'btn btn-success', 'type' => 'submit', 'title' => 'Сохранить и закрыть']) ?>

<?php else: ?>

<?= Html::button('<i class="fa fa-fw fa-chevron-down"></i> Создать', ['class' => 'btn btn-success', 'type' => 'submit', 'title' => 'Сохранить новый объект']) ?>

<?php endif; ?>