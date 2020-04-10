<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RssChannel;

/**
 * RssChannelSearch represents the model behind the search form of `common\models\RssChannel`.
 */
class RssChannelSearch extends RssChannel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'limit'], 'integer'],
            [['title', 'alias', 'container_template', 'item_template', 'filter'], 'safe'],
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
        $query = RssChannel::find();

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
            'limit' => $this->limit,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'container_template', $this->container_template])
            ->andFilterWhere(['like', 'item_template', $this->item_template])
            ->andFilterWhere(['like', 'filter', $this->filter]);

        return $dataProvider;
    }
}
