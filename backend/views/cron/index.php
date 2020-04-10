<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CmsCronScheduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Расписание задач крона';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="cms-cron-schedule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'command',
            'schedule',
            'is_active',
            'params',
            'description',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}{delete}',
                'buttons'=>[
                    'view'=>function($url, $model, $key) {
                        return Html::a("<i class='fa fa-play' aria-hidden='true'></i>",$url);
                    }
                ],
            ]
        ],
    ]); ?>

    <hr />

    <img src="http://ts-soft.ru/blog/wp-content/uploads/2013/12/pngbase6494d01710e62a4eee.png" />

    <h4>Примеры</h4>

    <p><code>* * * * *</code> каждую минуту</p>
    <p><code>20 * * * *</code> каждый час в 20 минут (00:20, 01:20, 02:20 и т.д.)</p>
    <p><code>00 12 * * *</code> каждый день в 12:00</p>
    <p><code>00 12 1 * *</code> 1 числа каждого месяца в 12:00</p>
    <p><code>00 12 10 2 *</code> каждый февраль (2), 10 числа в 12:00</p>
    <p><code>00 12 * * 7</code> каждое воскресенье(7), в 12:00</p>


</div>
