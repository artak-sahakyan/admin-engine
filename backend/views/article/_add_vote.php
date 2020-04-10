<?php

use common\helpers\CategoryHelper;
use yii\helpers\{ Html, ArrayHelper };
use kartik\select2\Select2;
use common\widgets\ButtonGroupWidget;
use unclead\multipleinput\MultipleInput;
use common\models\{
    BannerGroup,
    ArticleCategory
};
use \yii\web\JsExpression;

/** @var  $modelVoting \common\models\Voting */

?>
<div class="voting-form">
    <div class="alert alert-success" role="alert" id="voting-form-success" style="display: none">
        Сохранено
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<div class="col-md-8">
    <div class="form-group">
        <?= $form->field($modelVoting, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group">
        <?= $form->field($modelVoting, 'title')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="checkbox">
        <?= $form->field($modelVoting, 'show_sidebar')->checkBox(['label' => 'Отображать в сайдбаре', 'selected' => $modelVoting->show_sidebar]) ?>
    </div>
    <div class="checkbox">
        <?= $form->field($modelVoting, 'show_bottom')->checkBox(['label' => 'Отображать внизу статьи', 'selected' => $modelVoting->show_bottom]) ?>
    </div>
    <div class="checkbox">
        <?= $form->field($modelVoting, 'show_main')->checkBox(['label' => 'Отображать на главной', 'selected' => $modelVoting->show_main]) ?>
    </div>
    <div class="checkbox">
        <?= $form->field($modelVoting, 'show_article')->checkBox(['label' => 'Отображать в статье', 'selected' => $modelVoting->show_article]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($modelVoting, 'bannerGroups')->widget(Select2::class, [
            'data' => ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true,  'id' => 'banner-group-select'],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 100
            ],
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= $form->field($modelVoting, 'articleCategories')->widget(Select2::class, [
            'data' => ArrayHelper::map(CategoryHelper::getCatogiriesForDropDownm(ArticleCategory::find()->orderBy('sort')->asArray()->all(), '-'), 'id', 'title'),
            'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true, 'id' => 'article-categories-select'],
            'pluginOptions' => [
                'tags' => true,
                'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 100
            ],
        ]);
        ?>
    </div>

    <div class="form-group">

        <?= $form->field($modelVoting, 'votingArticles')->widget(Select2::class, [
            'initValueText' => $modelVoting->votingArticles ? array_keys($modelVoting->votingArticles) : [],
            'options' => ['placeholder' => 'Ничего не выбрано', 'multiple' => true, 'id' => 'voting-articles-select'],
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
            <?= Html::button('<i class="fa fa-fw fa-chevron-down"></i> Сохранить', ['id' => 'add_article_voting', 'class' => 'btn btn-success', 'type' => 'submit', 'title' => 'Сохранить и закрыть', 'data-url' => \yii\helpers\Url::toRoute(['voting/create-voting'])]) ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <?= $form->field($modelVoting, 'answers')->widget(MultipleInput::class, [
            'max' => 10,
            'min' => 1,
            'allowEmptyList' => false,
            'enableGuessTitle' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            'columns' => [
                [
                    'name' => 'answers',
                    'title' => 'Варианты ответов',
                    'value' => function ($data) {
                        return $data['title'];
                    },
                ]
            ],
        ])
            ->label(false);
        ?>
    </div>
</div>
</div>

<?php
$this->registerJs("

function setSelect2Data(obj) {
    var arr = [];
    for(i in obj) {
        arr.push(obj[i].id);
    }
    return arr;
}

$(document).on('click', '#add_article_voting', function(event) {
    event.preventDefault();
    var form = $('form').find('.voting-form input').serialize();
    var url = $(this).attr('data-url');
    var select2 = {};
    var banner_group_select = $(\"#banner-group-select\").select2(\"data\");
    var article_categories_select = $(\"#article-categories-select\").select2(\"data\");
    var voting_articles_select = $(\"#voting-articles-select\").select2(\"data\");
  
    select2['bannerGroups'] = setSelect2Data(banner_group_select);
    select2['articleCategories'] = setSelect2Data(article_categories_select);
    select2['votingArticles'] = setSelect2Data(voting_articles_select);
       
    $.ajax({
        method: \"POST\",
        url: url,
        data: {form: form, select2: JSON.stringify(select2)}
    }).done(function (data) {
       console.log(data);
        
       if(data != \"success\") {
          for(attr in data) {
            $('.field-' + attr).addClass('has-error').find('.help-block').html(data[attr]);
          } 
          $('#voting-form-success').hide();
       } else {
            let reqAttr = ['voting-name', 'voting-title'];
            for(attr of reqAttr) {
            $('.field-' + attr).removeClass('has-error').addClass('has-success').find('.help-block').html('');
          }
          $('#voting-form-success').show();
       }
         
    }); 
   
})");

?>

