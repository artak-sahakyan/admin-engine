<?php
use common\models\Admin;
use common\helpers\EditableHelper;
use common\models\ArticleCategory;
use yii\helpers\Url;
use yii\helpers\Html;
use \backend\models\Article;
use yii\widgets\Pjax;
use kartik\popover\PopoverX;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Анализ заголовков';
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->params['limits'] = $limits;
$this->params['domain'] = Url::base(true);
?>
<div class="article-index">

    <?= $this->render('_headers_search', ['model' => $searchModel, 'limits' => $limits]); ?>

    <div class="box">
 
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                        <?=\common\widgets\PageSizesCountWidget::widget(['model' => $searchModel, 'action' => 'unpublished']) ?>
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>

                <?= \kartik\grid\GridView::widget([
                    'id' => 'article-grid',
                    'layout' => "{summary}\n{items}\n<div align='right'>{pager}</div>",
                    'dataProvider' => $dataProvider,
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span>",
                    'responsive'=>true,
                    'hover'=>true,
                    'columns' => [
                        [
                            'attribute' => 'article_id',
                            'format' => 'raw',
                            'filter' => false,
                            'headerOptions' => ['style' => 'width:2%'],
                            'value' => function ($model) {
                                
                                return Html::a($model->article->id, $model->article->getUrl(), ['target' => '_blank', 'title' => $model->article->title]);
                            },
                        ],
                        [
                            'attribute' => 'chapters',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('chapters', $model->chapters, $this->params['limits']['chapters']);
                            },
                        ],
                        [
                            'attribute' => 'h1',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('h1', $model->h1, $this->params['limits']['h1']);
                            },
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('title', $model->title, $this->params['limits']['title']);
                            },
                        ],
                        [
                            'attribute' => 'description',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('description', $model->description, $this->params['limits']['description']);
                            },
                        ],
                        [
                            'attribute' => 'keywords',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('keywords', $model->keywords, $this->params['limits']['keywords']);
                            },
                        ],
                        [
                            'attribute' => 'alt',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('alt', $model->alt, $this->params['limits']['alt']);
                            },
                        ],
                        [
                            'attribute' => 'text',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('text', $model->text, $this->params['limits']['text']);
                            },
                        ],
                        [
                            'attribute' => 'baden_points',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('baden_points', $model->baden_points, $this->params['limits']['baden_points']);
                            },
                        ],
                        [
                            'attribute' => 'bigram',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('bigram', $model->bigram, $this->params['limits']['bigram']);
                            },
                        ],
                        [
                            'attribute' => 'trigram',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('trigram', $model->trigram, $this->params['limits']['trigram']);
                            },
                        ],
                        [
                            'attribute' => 'word_density',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('word_density', $model->word_density, $this->params['limits']['word_density']);
                            },
                        ],
                        [
                            'attribute' => 'miratext_water',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('miratext_water', $model->miratext_water, $this->params['limits']['miratext_water']);
                            },
                        ],
                        [
                            'attribute' => 'countMiratextWords',
                            'label' => 'Слова', 
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                $count = (substr_count($model->miratext_words, ';') / 3);
                                $rows = array_chunk(explode(';', $model->miratext_words), 3);
                                $table = '';
                                foreach ($rows as $row) {
                                    $table = $table . '<tr><td>' . implode('</td><td>', $row) . '</td></tr>';
                                }

                                return ($count > 0) ? PopoverX::widget([
                                    'header' => 'Всего слов: ' . $count,
                                    'placement' => PopoverX::ALIGN_LEFT,
                                    'size' => PopoverX::SIZE_LARGE,
                                    'content' => '<table class="table table-bordered"><tr><th>Слово</th><th>Повторений</th><th>Плотность</th></tr>' . $table . '</table>',
                                    'toggleButton' => ['label'=> $count, 'class'=>'btn btn-default'],
                                ]) : 0;
                            },
                        ],
                        [
                            'attribute' => 'miratext_bigram',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('miratext_bigram', round($model->miratext_bigram, 2), $this->params['limits']['miratext_water']);
                            },
                        ],
                        [
                            'attribute' => 'miratext_trigram',
                            'format' => 'raw',
                            'filter' => false,
                            'value' => function ($model) {
                                return $model->colored('miratext_trigram', round($model->miratext_trigram, 2), $this->params['limits']['miratext_water']);
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{edit} {view}{check} {delete}',
                            'buttons'=>[
                                'edit'=>function($url, $model, $key) {
                                    return Html::a("<i class='glyphicon glyphicon-pencil' aria-hidden='true'></i>", '/admin/article/update?id='.$model->article_id, ['target' => '_blank', 'title' => 'Редактировать']);
                                },
                                'view'=>function($url, $model, $key) {
                                    return Html::a("<i class='fa fa-eye' aria-hidden='true'></i>", $model->article->getUrl(), ['target' => '_blank', 'title' => 'Просмотр']);
                                },
                                'check'=>function($url, $model, $key) {
                                    return Html::a("<i class='fa fa-refresh' style='margin-left: 10px' aria-hidden='true'></i>",'', ['id' => 'refresh-analysis']);
                                }
                            ]
                        ]
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>

<style type="text/css">
    span.red-cell {
        background-color: red !important;
        color: white;
        display: block;
        padding: 4px;
    }
</style>

<script type="text/javascript">
    document.querySelector('#article-grid tr:first-child').insertAdjacentHTML('beforebegin', '<tr><th colspan="12">Nopyx</th><th colspan="4">Miratext</th></tr>');
</script>