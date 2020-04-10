<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SeohideSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

$this->title = 'Орфографические ошибки';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-spelling-index">

    <?= $this->render('_additional', [
        'action' => $data['action'],
        'running' => $data['running'],
        'cronId' => $data['cronId'],
        'lastRun' => $data['lastRun'],
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:5%; white-space: normal;'],
                'value' => function ($model) {
                    if(isset($model->article)) {
                        return Html::a($model->article_id, Url::toRoute('/article/update?id=' . $model->article_id), ['target' => '_blank']);
                    }
                },
            ],
            [
                'attribute' => 'article_id',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:30%; white-space: normal;'],
                'value' => function ($model) {
                    if(isset($model->article)) {
                        return Html::a($model->article->title, Url::toRoute('/article/update?id=' . $model->article_id), ['target' => '_blank']);
                    }
                },
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:30%; white-space: normal;'],
                'value' => function ($model) {
                    if(!empty($model->content)) {
                        $errors = array_map(function($data) use($model){
                            return Html::a($data, '#', ['class' => 'add-except', 'data-article-id' => $model->article_id, 'data-url' =>  \yii\helpers\Url::toRoute(['article-spelling-except/create-new'])]);
                        }, unserialize($model->content));
                        return implode(', ', $errors);
                    }
                },
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    if(!empty($model->title)) {
                        $errors = array_map(function($data) use($model){
                            return Html::a($data, '#', ['class' => 'add-except', 'data-article-id' => $model->article_id, 'data-url' =>  \yii\helpers\Url::toRoute(['article-spelling-except/create-new'])]);
                        }, unserialize($model->title));
                        return implode(', ', $errors);
                    }
                },
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => function($model) {
                    return ($model->updated_at) ? date('Y-m-d', $model->updated_at) : null;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{recheck}',
                'header' => 'Управление',
                'buttons' => [
                    'recheck' => function($url, $model, $key) {
                        return Html::a("<i class='fa fa-refresh' style='margin-left: 10px' aria-hidden='true'></i>",['/article-spelling/update-one', 'article_id' => $model->article_id], ['id' => 'refresh-' . $model->article_id]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>


<?php
$this->registerJs("


$(document).on('click', '.add-except', function(){
    var self = $(this);
    let word = self.html();
    let url = self.attr('data-url');
            
    let excepts = $('.add-except').parent().each(function() {
    
            var links = $(this).find('a').each(function() {
          
                let text = $(this).text().trim().toLocaleLowerCase();
                if(text ===  word.toLocaleLowerCase().trim()) {
                    $(this).get(0).remove();
                } 
            })
            
            var filtered = $(this).html().split(',').filter(function(e){return e.trim()}).join(',');
            $(this).html(filtered)
    });
    
    let msg =  success(word);
        $('#w2-success').length ? $('#w2-success').replaceWith(msg) : $('section.content').prepend(msg);     
    
    
    $.post(url, {word: word}, function(data) {
        
    });
    
    function success(word) {
        return `<div id=\"w2-success\" class=\"alert-success alert fade in\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>
                        <i class=\"icon fa fa-check\"></i>Добавлено слово << ` + word + ` >> в список слов-исключений
                </div>`;
    }
});

");

?>