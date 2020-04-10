<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\{ RssHelper, EditableHelper };
/* @var $this yii\web\View */
/* @var $searchModel backend\models\RssChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rss каналы';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="rss-channel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать Rss Канал', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'title',
                            'content' => function($data){
                                return EditableHelper::text($data, 'title');
                            },
                        ],
                        [
                            'attribute' => 'alias',
                            'content' => function($data){
                                return EditableHelper::text($data, 'alias');
                            },
                        ],
                        [
                            'attribute' => 'files',
                            'label' => 'Кол-во файлов',
                            'format' => 'text',
                            'content' => function($data) {
                                return count(RssHelper::getAllFiles($data->alias));
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
