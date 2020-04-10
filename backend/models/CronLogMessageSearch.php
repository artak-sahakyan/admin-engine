<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CronLogMessage;

/**
 * CronLogMessageSearch represents the model behind the search form of `backend\models\CronLogMessage`.
 */
class CronLogMessageSearch extends CronLogMessage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cron_log_id',], 'integer'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d'],
            [['message'], 'safe'],
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
        $query = CronLogMessage::find()->where(['cron_log_id' => $params['id']]);

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
            'cron_log_id' => $this->cron_log_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        if (isset($this->created_at)) {
            $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at)]);
        }

        return $dataProvider;
    }

    public function allColumns()
    {
        return [
            'id',
            'cron_log_id',
            'message' => [
                'attribute' => 'message',
                'options' => ['style' => 'width: 70%;'],
            ],
            'created_at' => [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return ($model->created_at) ? date('Y-m-d H:i:s', $model->created_at) : null;
                },
            ],
        ];
    }
}
