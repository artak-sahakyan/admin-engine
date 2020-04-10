<?= \kartik\grid\GridView::widget([
    'layout' => '{items}',
    'dataProvider' => $dataProvider,
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
]); ?>