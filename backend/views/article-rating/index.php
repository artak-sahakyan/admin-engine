<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleRatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рейтинги';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-rating-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'article_id',
                        'label' => 'ID статьи'
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->article) ? $model->article->getLink(true) : null;
                        },
                    ],
                    'positive',
                    'negative',
                    [
                        'attribute' => 'count_comments',
                        'label' => 'Кол-во комментариев',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return count($model->comments);
                        },
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
