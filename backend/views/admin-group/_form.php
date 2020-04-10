<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AdminGroup;

/* @var $this yii\web\View */
/* @var $model common\models\AdminGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'home_url')->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'allow_change_publisher_and_poster')->checkbox()?>

    <?=  $form->field($model, 'show_only_own_posts')->checkbox()?>

    <div class="grid">

    <?php $i = 1; ?>
    <?php foreach($this->params['actions'] as $controller => $actions): ?>

    <?php if($i % 3 == 1): ?>
    <ul>
    <?php endif ?>

        <li>
            <ul class="allow-actions">
                <li><label><input type="checkbox" class="group-highlight" /> <?= AdminGroup::showHeader($controller) ?></label></li>
                <li>
                    <ul>
                        <?php foreach ($actions as $action): ?>
                        <li>
                            <label>
                                <?php
                                $allowActions = $this->params['adminGroup']['allow_actions'];
                                $checked = !empty($allowActions[strtolower(AdminGroup::camelToDish($controller))][strtolower(AdminGroup::camelToDish($action))]) ? 'checked="checked"' : '';
                                ?>
                                <input type="checkbox"
                                       name="allow_actions[<?= strtolower(AdminGroup::camelToDish($controller)) ?>][<?= strtolower(AdminGroup::camelToDish($action)) ?>]"
                                       value="1"
                                    <?= $checked ?>
                                />
                                <?= AdminGroup::showHeader($action) ?>
                            </label>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </li>
            </ul>
        </li>

    <?php if($i % 3 == 0): ?>
    </ul>
    <?php endif ?>

    <?php $i++ ?>

    <?php endforeach; ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
