<?php

namespace backend\models;

use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\{ BannerGroup, Article };

/**
 * BannerGroupSearch represents the model behind the search form of `common\models\BannerGroup`.
 */
class BannerGroupSearch extends BannerGroup implements SearchInterface
{
    public $pageSize;
    public $articlesCount;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'show_default_group', 'articlesCount'], 'integer'],
            [['name', 'pageSize', 'articlesCount'], 'safe']
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
        $query = BannerGroup::find();

        $articles_table = Article::tableName();
        $banner_groups_table = BannerGroup::tableName();
        $articles_count_sql = "(select count(*) from $articles_table pt where pt.banner_group_id = $banner_groups_table.id)";

        $query->select(['*', $articles_count_sql . " as articlesCount"]);

        //$query->compare($articles_count_sql, $this->articlesCount);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort'=> ['defaultOrder' => ['articlesCount'=>SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['articlesCount'] = [
            'asc' => ['articlesCount' => SORT_ASC],
            'desc' => ['articlesCount' => SORT_DESC],
        ];

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
            'show_default_group' => $this->show_default_group,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
