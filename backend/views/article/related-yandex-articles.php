<?php

use backend\models\Article;
use common\helpers\{ EditableHelper, FilesHelper };
use common\models\ArticleCategory;
use kartik\daterange\DateRangePicker;
use yii\helpers\{ ArrayHelper, Html, Url };
use yii\grid\GridView;
use yii\widgets\{ ActiveForm, Pjax };

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleRelatedYandexSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перелинковка';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="article-related-yandex-index">

    <p>
        Последнее полное обновление <span><?=FilesHelper::getLastRelatedArticlesFullUpdateTime() ?></span>
    </p>
    <p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#yandexloginModal">
            Запустить полное обновление
        </button>
    </p>

    <p>
        Статьи для которых собрано менее 20 похожих <span><strong><?=$searchModel->getLessThenArticles(20);?></strong></span>
    </p>
    <?php Pjax::begin(['id' => 'grid_related_pjax'])?>
    <p>
        <?php $form = ActiveForm::begin([
            'id' => 'grid_related_form',
            'action' => ['related-yandex-articles'],
            'method' => 'get',
        ]); ?>

        <?= $form->field($searchModel, 'showLessThen20')->hiddenInput()->label(false); ?>
        <?= Html::button(($searchModel->showLessThen20) ? 'Не показать' :'Показать', ['class' => 'btn btn-primary', 'id' => 'related-yandex-less']); ?>
        <?php ActiveForm::end(); ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <?= GridView::widget([
                    'options' => ['class' => ''],
                    'id' => 'related-yandex-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => '',
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'headerOptions' => ['style' => 'width:2%'],
                        ],
                        [
                            'attribute'     => 'id',
                            'headerOptions' => ['style' => 'width:2%'],
                            'label'         => 'ID',
                            'format'        => 'raw',
                            'value'         => function($model) {
                                return Html::a($model->id, Url::toRoute('/article/update?id=' . $model->id), ['target' => '_blank']);
                            }
                        ],

                        'title' => [
                            'attribute' => 'title',
                            'label'     => 'Заголовок',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->getLink(true);
                            },
                        ],
                        'main_query',
                        'category_id' => [
                            'attribute' => 'category_id',
                            'enableSorting' => false,
                            'filter' => $searchModel->getAllCategories(),
                            'filterInputOptions' => [
                                'class' => 'form-control',
                                'id' => 'category_id'
                            ],
                            'content' => function($model)  use($searchModel){

                                $attribute = 'category_id';
                                $relation = 'category.title';
                                return EditableHelper::dropdown($model, $attribute, $relation, $searchModel->getAllCategories());
                            },
                        ],
                        'relatedArticles' => [
                            'attribute' => 'relatedArticles',
                            'contentOptions' => ['class' => 'text-center'],
                            'format'    => 'raw',
                            'label'     => 'Соброно похожих',
                            'value' => function (Article $model) {
                                if($urls =  $model->renderRelatedArticles()) {
                                    if($urls['inactive']) {
                                        $inactive = '<div style="display: none;">'
                                        . implode('<br>', $urls['inactive'])
                                        . '</div>'
                                        . '<div align="center"><a href="#" class="relatet-inactive"><span>Показать</span> (' . count($urls['inactive']) .')</a></div>';
                                    } else {
                                        $inactive = '';
                                    }

                                    return implode('<br />', $urls['active']) . $inactive ;
                                }
                            },
                        ],
                        [
                            'attribute' => 'updated_related',
                            'label'     => 'Обовлено',
                            'value'     => function($model) {
                                $updated_at = ArrayHelper::getColumn($model->relatedYandexArticles, 'updated_at');
                                $updated_at = (!$updated_at) ? null : max($updated_at);
                                return ($updated_at) ? date('Y-m-d',$updated_at) : null;
                            },
                            'filter'    => '<div class="input-group drp-container">' . DateRangePicker::widget([
                                'name'=>'date_range_updated_related',
                                'id' => 'date_range_updated_related',
                                'model' => $searchModel,
                                'attribute' => 'updated_related',
                                'convertFormat'=>true,
                                'useWithAddon'=>false,
                                'pluginOptions'=>[
                                    'locale'=>[
                                        'format'=>'Y-m-d',
                                        'separator'=>' to ',
                                    ],
                                    'opens'=>'left'
                                ],
                                'pluginEvents'=>[
                                    'cancel.daterangepicker'=>"function(ev, picker) {\$('#articlesearch-updated_related').val('');$('#related-yandex-grid').yiiGridView('applyFilter'); }"
                                ]
                            ]) . '<i style="padding: 10px 1px;font-size: 15px" class="fas fa-calendar-alt"></i></div>'

                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}{delete}',
                            'header' => 'Управление',
                            'buttons'=>[
                                'update'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-refresh' style='margin-right: 10px' aria-hidden='true'></span>",Url::toRoute('/article/update-related?id=' . $model->id), ['id' => 'update-related']);
                                },
                                'delete'=>function($url, $model, $key) {
                                    return Html::a("<span class='glyphicon glyphicon-trash' aria-hidden='true'></span>",Url::toRoute('/article/delete-related-articles?id=' . $model->id), [ 'title' => 'Удалить',  'data-confirm' => 'Вы уверены, что хотите удалить?']);
                                },

                            ]
                        ],
                    ]
                ]); ?>
            </div>
        </div>
    </div>

    <? Pjax::end() ?>
</div>


<div class="modal fade" id="yandexloginModal" tabindex="-1" role="dialog" aria-labelledby="yandexloginModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" align="center"><strong>Введите имя и ключ яндекс профиля</strong></h5>
            </div>
            <form action="<?=Url::to('update-all-related') ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="yandex-name">Имя в яндексе</label>
                        <input type="text"  name="yandex-name" class="form-control" id="yandex-name">
                    </div>
                    <div class="form-group">
                        <label for="yandex-key">Ключ яндекса</label>
                        <input type="text" name="yandex-key" class="form-control" id="yandex-key">
                    </div>
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Запустить</button>
                </div>
            </form>
        </div>
    </div>
</div>
