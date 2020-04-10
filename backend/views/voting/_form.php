<?php

use common\helpers\CategoryHelper;
use yii\helpers\{ Html, ArrayHelper };
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\widgets\ButtonGroupWidget;
use unclead\multipleinput\MultipleInput;
use common\models\{
    BannerGroup,
    ArticleCategory,
    Article
};
use \yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Voting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="voting-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-8">
        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="checkbox">
            <?= $form->field($model, 'show_sidebar')->checkBox(['label' => 'Отображать в сайдбаре', 'selected' => $model->show_sidebar]) ?>
        </div>
        <div class="checkbox">
            <?= $form->field($model, 'show_bottom')->checkBox(['label' => 'Отображать внизу статьи', 'selected' => $model->show_bottom]) ?>
        </div>
        <div class="checkbox">
            <?= $form->field($model, 'show_main')->checkBox(['label' => 'Отображать на главной', 'selected' => $model->show_main]) ?>
        </div>
        <div class="checkbox">
            <?= $form->field($model, 'show_article')->checkBox(['label' => 'Отображать в статье', 'selected' => $model->show_article]) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'bannerGroups')->widget(Select2::class, [
                        'data' => ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name'),
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
            <?= $form->field($model, 'articleCategories')->widget(Select2::class, [
                        'data' => ArrayHelper::map(CategoryHelper::getCatogiriesForDropDownm(ArticleCategory::find()->orderBy('sort')->asArray()->all(), '-'), 'id', 'title'),
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

            <?= $form->field($model, 'votingArticles')->widget(Select2::class, [
                        'initValueText' => array_keys($model->votingArticles),
                        'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true],
                        'pluginOptions' => [
                        'minimumInputLength' => 3,
                        'tokenSeparators' => [',', ' '],
                        'maximumInputLength' => 100,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/article/search-by-title']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                                'results' => new JsExpression('function(data) { return {results:data.results}; }'),
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(votingArticles) { return votingArticles.text; }'),
                            'templateSelection' => new JsExpression('function (votingArticles) { console.log(votingArticles);return votingArticles.text; }'),
                        ],
                ])->label('Статьи');
            ?>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= ButtonGroupWidget::widget(['model' => $model]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?= $form->field($model, 'answers')->widget(MultipleInput::class, [
                    'max'               => 10,
                    'min'               => 1,
                    'allowEmptyList'    => false,
                    'enableGuessTitle'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER,
                    'columns' => [
                        [
                            'name'  => 'answers',
                            'title'  => 'Варианты ответов',
                            'value' => function($data) {
                                return $data['title'];
                            },
                        ]
                    ],
                ])
                ->label(false);
            ?>  
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
