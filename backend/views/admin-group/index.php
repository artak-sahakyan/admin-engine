<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы пользователей';
$this->params['breadcrumbs'][] = ['label' => ' Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="admin-group-index">

    <?= $this->render('_additional'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'alias',
            'title',
            'home_url:url',
            [
                'label' => 'Пользователей',
                'value' => function($model) {
                    return $model->getAdminCount();
                }
            ],

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
