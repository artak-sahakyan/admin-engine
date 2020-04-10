<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\{ Expandable, PageSizesCountWidget };

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Потерянные места';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= $this->render('_additional_lost'); ?>

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
                        [
                            'label' => 'ID',
                            'attribute' => 'article_id',
                        ],
                        'title',
                        [
                        	'label' => 'Всего мест',
                            'attribute' => 'countBannerPlaces',
                        ],
                        [
                        	'label' => 'Кол-во блоков перелинковки',
                            'attribute' => 'countRelatedBlocks',
                        ],
                        [
                            'label' => 'Кол-во турбо мест',
                            'attribute' => 'countTurboBlocks',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn', 
                            'template'=>'{edit}', 
                            'buttons'=>[
                                'edit'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-pencil' style='margin-right: 10px' aria-hidden='true'></span>",Url::toRoute('/article/update?id=' . $model->article_id ), ['id' => 'update-article', 'title' => 'Редактировать статью']);
                                },
                            ]],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>