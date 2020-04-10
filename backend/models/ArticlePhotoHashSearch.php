<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticlePhotoHash;

/**
 * ArticlePhotoHashSearch represents the model behind the search form of `common\models\ArticlePhotoHash`.
 */
class ArticlePhotoHashSearch extends ArticlePhotoHash implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'article_id', 'type'], 'integer'],
            [['path', 'hash'], 'safe'],
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
        $query = ArticlePhotoHash::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'type' => $this->type,
        ]);

        $hashes = ArticlePhotoHash::find()
            ->select(['hash', 'COUNT(id) AS count'])
            ->andWhere(['type' => $this->type])
            ->groupBy(['hash'])
            ->having(['>', 'count', 1])
            ->column();

        $query->andFilterWhere(['in', 'hash', $hashes]);

        $query->joinWith(['article'])->orderBy('hash');

        return $dataProvider;
    }
}
