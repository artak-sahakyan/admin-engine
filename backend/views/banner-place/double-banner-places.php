<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\{ Expandable, PageSizesCountWidget };

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дублированные места';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= $this->render('_additional_double', compact('lastRun', 'running', 'action')); ?>

<div class="banner-index">
<div class="box">
        <div class="box-header">
            <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <?= \kartik\grid\GridView::widget([
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'responsive'=>true,
                    'hover'=>true,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'title' => [
                            'attribute' => 'title',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->getLink(true);
                            },
                        ],
                        'is_double_banner_place_manual_fix' => [
                            'attribute' => 'is_double_banner_place_manual_fix',
                            'format' => 'raw',
                            'value' => function($model){
                                return $model->is_double_banner_place_manual_fix ? 'Исправлено' : 'Ожидает исправления';
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn', 
                            'template'=>'{edit}', 
                            'buttons'=>[
                                'edit'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-pencil' style='margin-right: 10px' aria-hidden='true'></span>",Url::toRoute('/article/update?id=' . $model->id ), ['id' => 'update-article', 'title' => 'Редактировать статью']);
                                },
                            ]],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>