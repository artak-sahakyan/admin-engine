<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

    <div class="well">
        <?php Pjax::begin(['id' => 'grid_anchors_pjax'])?>
        <?= GridView::widget([
            'id' => 'anchors-grid',
            'dataProvider' => new ActiveDataProvider(['query' => $model->getArticleAnchor()]),
            'layout' => '{items}{pager}',
            'columns' => [
                'title',
                'wordstat_count',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update}{delete}',
                    'buttons'=>[
                        'update'=>function($url, $model, $key) {
                            return Html::button("<span class='glyphicon glyphicon-pencil'></span>", ['data-url' => Url::toRoute(['article-anchor/update', 'id' => $model->id]), 'class' => 'update-anchors', 'style' => 'margin-right:10px']);
                        },
                        'delete'=>function($url, $model, $key) {
                            return Html::button("<span class='glyphicon glyphicon-trash' aria-hidden='true'></span>", ['class' => 'delete-anchors', 'data-url' => Url::toRoute(['article-anchor/delete', 'id' => $model->id]), 'data-id'=> $model->id]);
                        }
                    ]
                ]
            ],


        ]); ?>

        <?php Pjax::end()?>
    </div>


<?php Pjax::begin(['id' => 'anchors_pjax'])?>
<div class="modal fade" id="newAnchor" tabindex="-1" role="dialog" aria-labelledby="newAnchor" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div>
    </div>
</div>
<?php Pjax::end()?>