<?php

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>


    <div class="well">

        <?php Pjax::begin(['id' => 'grid_analyse_pjax'])?>
        <?= GridView::widget([
            'id' => 'seo-analize-grid',
            'dataProvider' => new ActiveDataProvider(['query' => $model->getNauseaOfArticle()]),
            'layout' => '{items}{pager}',
            'columns' => [
                [
                    'attribute' => 'baden_points',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('baden_points', $model->baden_points);
                    },
                ],
                [
                    'attribute' => 'bigram',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('bigram', $model->bigram);
                    },
                ],
                [
                    'attribute' => 'trigram',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('trigram', $model->trigram);
                    },
                ],
                [
                    'attribute' => 'word_density',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('word_density', $model->word_density);
                    },
                ],
                [
                    'attribute' => 'title',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('title', $model->title);
                    },
                ],
                [
                    'attribute' => 'description',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('description', $model->description);
                    },
                ],
                [
                    'attribute' => 'keywords',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('keywords', $model->keywords);
                    },
                ],
                [
                    'attribute' => 'h1',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('h1', $model->h1);
                    },
                ],
                [
                    'attribute' => 'chapters',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('chapters', $model->chapters);
                    },
                ],
                [
                    'attribute' => 'alt',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('alt', $model->alt);
                    },
                ],
                [
                    'attribute' => 'text',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->colored('text', $model->text);
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{view}{check}',
                    'buttons'=>[
                        'view'=>function($url, $model, $key) {
                            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? true;
                            return Html::a("<i class='fa fa-eye' aria-hidden='true'></i>",\common\behaviors\ParserBehavior::END_POINT . '?link=' . Url::to($model->article->getUrl(), $proto), ['target' => '_blank', 'title' => 'Просмотр']);
                        },
                        'check'=>function($url, $model, $key) {
                            return Html::a("<i class='fa fa-refresh' style='margin-left: 10px' aria-hidden='true'></i>",'', ['id' => 'refresh-analysis']);
                        }
                    ]
                ]
            ],


        ]); ?>
        <?php Pjax::end()?>

        <h4>Анализ загаловков</h4>

        <?php Pjax::begin(['id' => 'grid_analyse_headers'])?>
        <?= GridView::widget([
            'id' => 'seo-analize-grid2',
            'options' => ['class' => 'detail-grid-view table-responsive'],
            'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->generateHeader(),
                    'sort' => [
                        'attributes' => ['name', 'value', 'symbols'],
                    ],
                    'pagination' => [
                        'pageSize' => false,
                    ],]),
            'layout' => '{items}{pager}',
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => 'Элемент',
                    'options' => ['width' => '100px'],
                    'value' => function ($model) {
                        return $model['name'];
                    },
                ],
                [
                    'attribute' => 'value',
                    'format' => 'raw',
                    'label' => 'Значение',
                    'value' => function ($model) {
                        return $model['value'];
                    },
                ],
                [
                    'attribute' => 'symbols',
                    'label' => 'Символы',
                    //  'options' => ['width'=>'50px'],
                    'value' => function ($model) {
                        return  $model['symbols'];
                    },
                ]
            ],


        ]); ?>
        <?php Pjax::end()?>

        <h4>Анализ альтов</h4>

        <?php Pjax::begin(['id' => 'grid_analyse_alts'])?>
        <?= GridView::widget([
            'id' => 'seo-analize-grid3',
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->generateAlts(),
                'sort' => [
                    'attributes' => ['alt', 'symbols'],
                ],
                'pagination' => [
                    'pageSize' => false,
                ],]),
            'layout' => '{items}{pager}',
            'columns' => [
                [
                    'attribute' => 'alt',
                    'label' => 'Альт',
                    'value' => function ($model) {
                        return $model['alt'];
                    },
                ],
                [
                    'attribute' => 'symbols',
                    'label' => 'Символы',
                    //  'options' => ['width'=>'50px'],
                    'value' => function ($model) {
                        return  $model['symbols'];
                    },
                ]
            ],


        ]); ?>
        <?php Pjax::end()?>


    </div>
