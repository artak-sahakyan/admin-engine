<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\EditableHelper;
use yii\helpers\ArrayHelper;
use common\models\AdminGroup;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$checkboxDataArray = [0 => 'Нет', 1 => 'Да'];
$adminGroups = ArrayHelper::map(AdminGroup::find()->asArray()->all(), 'id', 'title');
?>

<div class="admin-index">
    <?= $this->render('_additional'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'adminGroups',
                'format' => 'text',
                'filter' => $adminGroups,
                'filterInputOptions' => [
                    'class' => 'form-control', 
                    'prompt' => 'Не важно'
                ],
                'content' => function($data){
                    return implode(', ', ArrayHelper::map($data->adminGroups, 'id', 'title'));
                },
            ],
            [
                'attribute' => 'is_active',
                'label' => 'Вкл',
                'filter' => $checkboxDataArray,
                'filterInputOptions' => [
                    'class' => 'form-control', 
                    'prompt' => 'Не важно'
                ],
                'content' => function($data) use($checkboxDataArray) {
                    return EditableHelper::checkbox($data, 'is_active', $checkboxDataArray);
                },
            ],
            [
                'label' => 'Постер кол-во',
                'value' => function($model) {
                    return $model->getArticlesCount();
                }
            ],
            [
                'label' => 'Публицист кол-во',
                'value' => function($model) {
                    return $model->getArticlesPublisherCount();
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    

</div>
