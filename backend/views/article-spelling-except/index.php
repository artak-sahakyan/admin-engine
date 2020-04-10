<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSpellingExceptSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Слова-исключения';
$this->params['breadcrumbs'][] = ['label' => 'Орфографические ошибки', 'url' => '/admin/article-spelling'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-spelling-except-index">

    <?= $this->render('_additional', ['data' => $data]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{delete}'],
        ],
    ]); ?>


</div>
