<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AdminGroup */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => ' Пользователи', 'url' => '/admin/admin-user'];
$this->params['breadcrumbs'][] = ['label' => 'Группы пользователей', 'url' => '/admin/admin-group'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
\yii\web\YiiAsset::register($this);
?>
<div class="admin-group-view">

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
            'alias',
            'title',
            'home_url:url',
        ],
    ]) ?>

</div>
