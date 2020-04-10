<?php

use common\widgets\PageSizesCountWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\{ ArrayHelper, Url };
use common\models\{ BannerType, BannerDevice, BannerGroup, BannerPlace, BannerPartner };
use common\helpers\EditableHelper;

$this->title = 'Баннеры';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$placesDataArray = ArrayHelper::map(BannerPlace::find()->asArray()->all(), 'id', 'name');
$partnersDataArray = ArrayHelper::map(BannerPartner::find()->asArray()->all(), 'id', 'name');
$devicesDataArray = ArrayHelper::map(BannerDevice::find()->asArray()->all(), 'id', 'name');
$groupsDataArray = ArrayHelper::map(BannerGroup::find()->asArray()->all(), 'id', 'name');
$groupsDataArray[0] = 'Без группы';
$checkboxDataArray = [0 => 'Нет', 1 => 'Да'];

?>

<div class="banner-index">

    <p>
        <?= Html::a('Создать баннер', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('_search', [
        'model'             => $searchModel, 
        'placesDataArray'   => $placesDataArray,
        'partnersDataArray' => $partnersDataArray,
        'devicesDataArray'  => $devicesDataArray,
        'groupsDataArray'   => $groupsDataArray
    ]); ?>

    <?= $this->render('_additional', ['model' => $searchModel]); ?>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <?=PageSizesCountWidget::widget(['model' => $searchModel]) ?>
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?= \kartik\grid\GridView::widget([
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'responsive'=>true,
                    'hover'=>true,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                        ],
                        'id',
                        [
                            'attribute' => 'created_at',
                            'format' => 'date'
                        ],
                        [
                            'attribute' => 'place_id',
                            'content' => function($data) use($placesDataArray) {
                                return EditableHelper::dropdown($data, 'place_id', 'place', $placesDataArray);
                            },
                        ],
                        [
                            'attribute' => 'type_id',
                            'format' => 'text',
                            'content' => function($data){
                                return $data->type->name;
                            },
                        ],
                        [
                            'attribute' => 'partners',
                            'label' => 'Партнерки',
                            'format' => 'text',
                            'content' => function($data) use($partnersDataArray) {
                                return EditableHelper::select2($data, 'partners', BannerPartner::class, $partnersDataArray);
                            },
                        ],
                        [
                            'attribute' => 'service',
                        ],
                        [
                            'attribute' => 'name',
                            'content' => function($data){
                                return EditableHelper::text($data, 'name');
                            },
                        ],
                        [
                            'attribute' => 'device_id',
                            'content' => function($data) use($devicesDataArray) {
                                return EditableHelper::dropdown($data, 'device_id', 'device', $devicesDataArray);
                            },
                        ],
                        [
                            'attribute' => 'group_id',
                            'content' => function($data) use($groupsDataArray) {
                                return EditableHelper::dropdown($data, 'group_id', 'bannerGroup', $groupsDataArray);
                            },
                        ],
                        [
                            'attribute' => 'is_active',
                            'label' => 'Вкл',
                            'content' => function($data) use($checkboxDataArray) {
                                return EditableHelper::checkbox($data, 'is_active', $checkboxDataArray);
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template'=>'{update} {delete}',],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>
