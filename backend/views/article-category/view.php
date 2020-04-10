<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleCategory */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => '/admin/article-category'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="article-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'parent_id',
            'slug',
            'title',
            'h1Title',
            'metaTitle',
            'metaDescription',
            'metaKeywords',
            'text:ntext',
            'created_at',
            'updated_at',
            'is_medical'
        ],
    ]) ?>

</div>
