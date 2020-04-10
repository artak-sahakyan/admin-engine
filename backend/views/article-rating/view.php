<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */

$this->title = $model->article->title;
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => '/admin/article-rating'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="article-category-view">
    <div class="box">
        <div class="box-body">
            <div class="col-md-12">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>
                    <?= Html::a('Удалить статистику', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up "></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Положительных</span>
                        <span class="info-box-number"><?= $model->positive ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-thumbs-down "></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Отрицательных</span>
                        <span class="info-box-number"><?= $model->negative ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-striped">
                    <tbody><tr>
                        <th>Комментарии:</th>
                    </tr>
                    <?php foreach($model->comments as $comment): ?>
                        <tr><td><?= $comment ?></td></tr>
                    <?php endforeach; ?>
                </tbody></table>
            </div>
        </div>
    </div>
</div>
