<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticleCategory;

/**
 * ArticleCategorySearch represents the model behind the search form of `common\models\ArticleCategory`.
 */
class ArticleCategorySearch extends ArticleCategory implements SearchInterface
{
    public $pageSize;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_at', 'updated_at', 'is_medical'], 'integer'],
            [['slug', 'title', 'h1Title', 'metaTitle', 'metaDescription', 'metaKeywords', 'text', 'pageSize'], 'safe'],
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
        $query = ArticleCategory::find();

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
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_medical' => $this->is_medical
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'h1Title', $this->h1Title])
            ->andFilterWhere(['like', 'metaTitle', $this->metaTitle])
            ->andFilterWhere(['like', 'metaDescription', $this->metaDescription])
            ->andFilterWhere(['like', 'metaKeywords', $this->metaKeywords])
            ->andFilterWhere(['like', 'text', $this->text]);

        if ($this->parent_id === "0") {
            $query->orWhere('parent_id IS null');
        }

        return $dataProvider;
    }
}
