<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticleAdvertisingIntegration;

/**
 * ArticleAdvertisingIntegrationSearch represents the model behind the search form of `common\models\ArticleAdvertisingIntegration`.
 */
class ArticleAdvertisingIntegrationSearch extends ArticleAdvertisingIntegration implements SearchInterface
{
    public $pageSize;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'article_id', 'end_date', 'is_active'], 'integer'],
            [['text', 'name', 'pageSize'], 'safe'],
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
        $query = ArticleAdvertisingIntegration::find();

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

        if($this->pageSize) {
            $dataProvider->pagination->pageSize = $this->pageSize;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'article_id' => $this->article_id,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function getCheckboxDataArray() {
        return [1 => 'Да', 0 => 'Нет'];
    }


}
