<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php

    $categories = [];
    $params = ['ArticleCategorySearch' => ['parent_id' => null, 'pageSize' => 99999]];
    foreach($searchModel->search($params)->getModels() as $category) {
        $id = $category->id;
        $parentId = $category->parent_id;

        $categories[$id] = ['parent_id' => $parentId];
    }
    $categories = json_encode($categories);

$script = <<< JS
    var categoryDataMap = JSON.parse('$categories');
    if ($('.gridviewexpand .expand').length) {
        $('.gridviewexpand').gridviewexpand({
            dataMap: categoryDataMap
        });
    }
JS;
$this->registerJs($script);

?>

<div class="article-category-index">

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <!-- search-->
                    </div>
                </div>
                <?= \kartik\grid\GridView::widget([
                    'layout' => "{summary}\n{items}",
                    'defaultPagination' => 'all',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "<span style='display: block;text-align: right'>Элементов {end} из {totalCount}</span></p>",
                    'columns' => [
                        [
                            'value' => function($model, $key, $index, $column) {
                                return '';
                            },
                            'contentOptions' => [
                                'class' => ['expand']
                            ]
                        ],
                        'id',
                        'parent_id',
                        'slug',
                        'title',
                        'h1Title',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                    'options' => [
                        'class' => 'gridviewexpand'
                    ]
                ]); ?>
            </div>
        </div>
    </div>


</div>
