<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $articles list id */
/* @var $articlesUrl list article url */
/* @var $article common\models\Article */
/* @var $articleMeta common\models\ArticleMeta */
/* @var $form yii\widgets\ActiveForm */

$this->params['breadcrumbs'][] = ['label' => 'Опубликованные статьи', 'url' => '/admin/article'];
$this->title = 'Выгрузка статьи';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="article-export">
    <?php $form = ActiveForm::begin(); ?>
    <?php
        foreach ($articles as $articleExport) {
            echo Html::hiddenInput('articles[]', $articleExport);
        }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header" style="cursor: move;">
                    <h3 class="box-title">Excel</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($article, 'id')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($article, 'slug')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($article, 'title')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($article, 'main_query')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($articleMeta, 'meta_title')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($articleMeta, 'meta_keywords')->checkbox(['checked' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($articleMeta, 'meta_description')->checkbox(['checked' => true]) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::submitButton('Скачать файл', ['class' => 'btn btn-warning export']) ?>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header" style="cursor: move;">
                    <h3 class="box-title">Урлы</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <?= Html::textarea(null, $articlesUrl, ['class' => 'urls']) ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-warning buffer">Скопировать в буфер</button><br/><br/>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
