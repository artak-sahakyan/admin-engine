<?php

namespace backend\models;

use yii\base\Model;
use backend\interfaces\SearchInterface;
use yii\data\ActiveDataProvider;
use common\models\ArticleYoutube;

/**
 * ArticleYoutubeSearch represents the model behind the search form of `common\models\ArticleYoutube`.
 */
class ArticleYoutubeSearch extends ArticleYoutube implements SearchInterface
{
    public $pageSize;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'article_id', 'missed_position', 'created_at', 'updated_at', 'missed_updated_at'], 'integer'],
            [['missed_link', 'pageSize'], 'safe'],
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
        $query = ArticleYoutube::find()->where('missed_position IS NOT NULL AND missed_updated_at IS NOT NULL');

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
            'missed_position' => (is_numeric($this->missed_position) ? $this->missed_position- 1 : $this->missed_position),
            'missed_updated_at' => $this->missed_updated_at,
        ]);

        $query->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
