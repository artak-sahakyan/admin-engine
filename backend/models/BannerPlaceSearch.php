<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BannerPlace as BannerPlaceModel;

/**
 * BannerPlace represents the model behind the search form of `common\models\BannerPlace`.
 */
class BannerPlaceSearch extends BannerPlaceModel implements SearchInterface
{
    public $pageSize;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'alias', 'pageSize'], 'safe'],
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
        $query = BannerPlaceModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->pageSize) {
            $dataProvider->pagination->pageSize = $this->pageSize;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $query->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}
