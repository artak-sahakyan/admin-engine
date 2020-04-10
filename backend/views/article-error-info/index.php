<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleErrorInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ошибки от пользователей';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-error-info-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'summary' => "<span style='display: block;text-align: left'>количество присланных ошибок ({totalCount})</span></p>",
        'columns' => [
            'article_id' => [
                'attribute' => 'article_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="' . $model->article->getUrl() . '" target="_blank">' . $model->id . '</a>';
                },
            ],
            [
                'attribute' => 'error_in_text',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'max-width:780px; overflow:hidden'],
                'headerOptions' => ['style' => 'max-width:780px; overflow:hidden'],
            ],
            [
               'attribute' => 'date_send',
                'value' => function ($model) {
                    return ($model->date_send) ? date('Y-m-d H:i:s', $model->date_send) : null;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'header' => 'Управления'
            ],
        ],
    ]); ?>


</div>
