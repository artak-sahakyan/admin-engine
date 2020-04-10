<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\EditableHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ExpertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Эксперты';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$checkboxDataArray = [0 => 'Нет', 1 => 'Да'];
?>
<div class="expert-index">

    <p>
        <?= Html::a('Создать эксперта', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'username',
                        'email:email',
            // 'status',
                        [
                            'attribute' => 'created_at',
                            'format' => 'date'
                        ],
                        'articles_count',
                        [
                            'attribute' => 'is_expert',
                            'content' => function($data) use($checkboxDataArray) {
                                return EditableHelper::checkbox($data, 'is_expert', $checkboxDataArray);
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
