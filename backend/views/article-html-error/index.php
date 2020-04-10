<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleErrorInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ошибки разметки статей';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-html-errors-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_additional', ['data' => $data]); ?>

<div class="box">
        <div class="box-body">

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            [
                'attribute' => 'article_id',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model) {
                    
                    return Html::a($model->article_id, $model->article->getUrl(), ['target' => '_blank', 'title' => $model->article->title]);
                },
            ],
            'content' => [
                'attribute' => 'content',
                'label' => 'Ошибки',
                'format' => 'raw',
                'value' => function ($model) {
                    $errors = $model->getErrorArray();

                    return implode('<br>', $errors);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{edit}',
                'buttons'=>[
                    'edit'=>function($url, $model, $key) {
                        return Html::a("<i class='glyphicon glyphicon-pencil' aria-hidden='true'></i>", '/admin/article/update?id='.$model->article_id, ['target' => '_blank', 'title' => 'Редактировать']);
                    }
                ]
            ]
        ],
    ]); ?>


</div>
        </div>
    </div>
</div>
