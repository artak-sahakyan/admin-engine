<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use common\models\ArticleErrorInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleErrorInfo represents the model behind the search form of `common\models\ArticleErrorInfo`.
 */
class ArticleErrorInfoSearch extends ArticleErrorInfo implements SearchInterface
{
    public $title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'article_id', 'date_send'], 'integer'],
            [['error_in_text', 'title'], 'safe'],
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
        $query = ArticleErrorInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['article.title' => SORT_ASC],
            'desc' => ['article.title' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'article_id' => $this->article_id,
            'date_send' => $this->date_send,
        ]);

        $query->andFilterWhere(['like', 'error_in_text', $this->error_in_text]);

        return $dataProvider;
    }
}
