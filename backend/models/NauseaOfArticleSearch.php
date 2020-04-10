<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\NauseaOfArticle;

use backend\interfaces\SearchInterface;
/**
 * NauseaOfArticleSearch represents the model behind the search form of `common\models\NauseaOfArticle`.
 */
class NauseaOfArticleSearch extends NauseaOfArticle  implements SearchInterface
{
    public $pageSize;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'article_id'], 'integer'],
            [['chapters', 'h1', 'title', 'description', 'keywords', 'alt', 'text', 'baden_points', 'bigram', 'trigram', 'word_density'], 'number'],
            [['chapters', 'h1', 'title', 'description', 'keywords', 'alt', 'text', 'baden_points', 'bigram', 'trigram', 'word_density'], 'safe'],
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
        $query = NauseaOfArticle::find();

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
            'article_id' => $this->article_id
        ]);

        $query->andFilterWhere(['or',
            ['>','chapters', $this->chapters],
            ['>','h1', $this->h1],
            ['>','title', $this->title],
            ['>','description', $this->description],
            ['>','keywords', $this->keywords],
            ['>','alt', $this->alt],
            ['>','text', $this->text],
            ['>','baden_points', $this->baden_points],
            ['>','bigram', $this->bigram],
            ['>','trigram', $this->trigram],
            ['>','word_density', $this->word_density]
        ]);

        return $dataProvider;
    }
}
