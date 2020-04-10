<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\EditableHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VotingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опросы';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$checkboxDataArray = [0 => 'Нет', 1 => 'Да'];
?>
<div class="voting-index">
    <p>
        <?= Html::a('Создать опрос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'Шорткод',
                'filter' => false,
                'value' => function ($data) {
                    return '[voting id="'.$data->id.'"]';
                },
            ],
            [
                'attribute' => 'name',
                'content' => function($data){
                    return EditableHelper::text($data, 'name');
                },
            ],
            [
                'attribute' => 'title',
                'content' => function($data){
                    return EditableHelper::text($data, 'title');
                },
            ],
            [
                'attribute' => 'show_sidebar',
                'content' => function($data) use($checkboxDataArray) {
                    return EditableHelper::checkbox($data, 'show_sidebar', $checkboxDataArray);
                },
            ],
            [
                'attribute' => 'show_bottom',
                'content' => function($data) use($checkboxDataArray) {
                    return EditableHelper::checkbox($data, 'show_bottom', $checkboxDataArray);
                },
            ],
            [
                'attribute' => 'show_main',
                'content' => function($data) use($checkboxDataArray) {
                    return EditableHelper::checkbox($data, 'show_main', $checkboxDataArray);
                },
            ],
            [
                'attribute' => 'show_article',
                'content' => function($data) use($checkboxDataArray) {
                    return EditableHelper::checkbox($data, 'show_article', $checkboxDataArray);
                },
            ],
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
