<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CmsCronSchedule */


$this->title = 'Запуск команды : ' . $model->command;
$this->params['breadcrumbs'][] = ['label' => 'Cms Cron Schedules', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => $this->title];

\yii\web\YiiAsset::register($this);
?>
<div class="cms-cron-schedule-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="status"><span class="label label-warning">Выполняется...</span></p>
    <div class="command"><pre></pre></div>
</div>

<?php
$url = Url::toRoute(['cron/check', 'id' => $model->id]);
$this->registerJs("
    let url = '$url';
    function process() {
        $.get(url, function(data){
            console.log(data);
            if(data.content.length) {
                $('.command pre').html(data.content);
            }
            if(data.status != 'running') {
                clearInterval(interval);
                $('.status span').removeClass('label-warning').addClass('label-success').html('Закончен')
                return false;   
            }
        }, 'json')
    }
    let interval = setInterval(process, 2500);
");


