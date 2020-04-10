<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CronLog;
use yii\helpers\Html;

/**
 * SerachCronLog represents the model behind the search form of `backend\models\CronLog`.
 */
class CronLogSearch extends CronLog implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'progress',], 'integer'],
            [['created_at', 'updated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['command', 'status',], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CronLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'progress' => $this->progress,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'command', $this->command])
            ->andFilterWhere(['like', 'status', $this->status]);

        if (isset($this->created_at)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at)]);
        }
        if (isset($this->updated_at)) {
            $query->andFilterWhere(['>=', 'updated_at', strtotime($this->updated_at)]);
        }

        return $dataProvider;
    }

    public function allCollumns()
    {
        return [
            'id',
            [
                'attribute' => 'command',
                'format' => 'raw',
                'value' => function($model){
                    if ($model->getCronLogMessagesSize($model->id) > 0) {
                        return Html::a($model->command, ['/cron-log/view', 'id' => $model->id]);
                    }

                    return $model->command;
                },
            ],
            'status',
            'progress',
            'created_at' => [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return ($model->created_at) ? date('Y-m-d H:i:s', $model->created_at) : null;
                },
            ],
            'updated_at' => [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return ($model->updated_at) ? date('Y-m-d H:i:s', $model->updated_at) : null;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'visibleButtons' => [
                    'view' => function($model){
                        if ($model->getCronLogMessagesSize($model->id)) {
                            return true;
                        }

                        return false;
                    },
                ],
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        return Html::a(
                            "<i class='glyphicon glyphicon-eye-open' style='margin-left: 10px' aria-hidden='true'></i>",
                            ['/cron-log/view/', 'id' => $model->id, 'page' => 999999]
                        );
                    }
                ],
            ],
        ];
    }
}
