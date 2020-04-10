<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => '/admin/article'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

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
            'category_id',
            'admin_id',
            'title',
            'slug',
            'description:ntext',
            'breadcrumbs',
            [
                'attribute' => 'meta_keywords',
                'value' => Yii::$app->formatter->asNtext($model->articleMeta->meta_keywords)
            ],
            [
                'attribute' => 'meta_description',
                'value' => Yii::$app->formatter->asNtext($model->articleMeta->meta_description)
            ],
            [
                'attribute' => 'meta_title',
                'value' => $model->articleMeta->meta_title
            ],
            'content:ntext',
           // 'image_extension',
            'created_at',
            'updated_at',
            'is_published',
            'published_at',
        ],
    ]) ?>

</div>
