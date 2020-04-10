<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'message',
            'ip',
            'datеtime',
            'rating',
            //'attaches',
            //'visible',
            //'user_id',
            //'nick',
            //'name',
            //'email:email',
            //'phone',
            //'avatar',
            //'chat_id',
            //'url:url',
            //'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
