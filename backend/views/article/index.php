<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\{ Expandable, PageSizesCountWidget, ModalWidget };

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опубликованные статьи';
$this->params['breadcrumbs'][] = ['label' => $this->title];
$url =  Url::toRoute(['article/get-article-urls-for-copy']);
$columnsFilter =  $this->render('_columns', ['model' => $searchModel]);
?>

<div class="article-index">

    <p>
        <?= Html::a('Добавить статью', ['create'], ['class' => 'btn btn-success']) ?>    
    </p>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <?= PageSizesCountWidget::widget(['model' => $searchModel]) ?>         
                </div>
                <div class="col-md-3">
                    <?= ModalWidget::widget(['title' => 'Таблица', 'content' => $columnsFilter]) ?>
                    <?= Html::button('Export', ['class' => 'btn btn-default export']) ?>
                </div>
            </div>

            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
               

            <?= \kartik\grid\GridView::widget([
                'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                'id' => $searchModel::GRID_ID,
                'dataProvider' => $dataProvider,
                'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                'responsive'=>true,
                'hover'=>true,
                'filterModel' => $searchModel,
                'rowOptions'=>function($model){
                    if($model->published_at >= time()){
                        return ['class' => 'disabled'];
                    }
                },
                'columns' => $searchModel->getColumns()
            ]); ?>
            </div>
        </div>
    </div>
</div>
