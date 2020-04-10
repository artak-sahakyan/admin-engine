<?php
use yii\helpers\Html;
?>

<div class="banner-search box box-primary">
    <div class="row">
        <div class="box-body">
            <div class="col-md-12">
                <div class="btn-group">
                  <?= Html::a('<i class="fa fa-plus"></i> Создать нового', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                  <?= Html::a('<i class="fa fa-group"></i> Группы пользователей', ['/admin-group'] , ['class' => 'btn btn-default btn-flat']) ?>
                </div>
            </div>               
        </div>
    </div>
</div>
