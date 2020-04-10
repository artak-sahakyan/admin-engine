<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use antishov\Morris;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Voting */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => ' Опросы', 'url' => '/admin/voting'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="voting-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $model->title ?></h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="chart" style="position: relative;">
                <?php
                    $answers = [];
                    foreach ($model->answers as $key => $answer) {
                        $answers[] = ['title' => $answer->title, 'count' => $answer->count];
                    }
                ?>
                <?= Morris\Bar::widget([
                     'element' => 'barChart',
                     'data' => $answers,
                     'xKey' => 'title',
                     'yKeys' => ['count'],
                     'labels' => ['Title'],
                     'hideHover' => 'auto',
                     'barColors' => ['rgb(82, 188, 211)', 'rgb(49, 167, 193)'],
                 ]);

                ?>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'title',
            'show_sidebar',
            'show_bottom',
            'show_main',
        ],
    ]) ?>

</div>
