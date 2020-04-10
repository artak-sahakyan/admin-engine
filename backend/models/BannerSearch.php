<?php

namespace backend\models;

use Yii;
use backend\interfaces\SearchInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\{ Banner, BannerVsPartners};

/**
 * BannerSearch represents the model behind the search form of `common\models\Banner`.
 */
class BannerSearch extends Banner implements SearchInterface
{
    public $partners;
    public $pageSize;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'is_active', 'is_scroll_fix', 'device_id', 'group_id', 'place_id'], 'integer'],
            [['name', 'content', 'partners', 'pageSize', 'service'], 'safe'],
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
        $query = Banner::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id'=>SORT_DESC]],
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
            'id'            => $this->id,
            'type_id'       => $this->type_id,
            'is_active'     => $this->is_active,
            'is_scroll_fix' => $this->is_scroll_fix,
            'device_id'     => $this->device_id,
            'place_id'      => $this->place_id,
            'service'       => $this->service,
        ]);

        if($this->group_id == '0') {
            $query->andFilterWhere([
                'or', 
                ['group_id' => 0], 
                ['is', 'group_id', new \yii\db\Expression('null')]
            ]);
        } else {
            $query->andFilterWhere(['group_id' => $this->group_id]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'content', $this->content]);

        if(array_key_exists('partners', Yii::$app->request->get('BannerSearch', []))) {
            $query->joinWith(['bannerVsPartners'])
                ->andFilterWhere(['partner_id' => Yii::$app->request->get('BannerSearch')['partners']]);
        }

        if(!$this->id) {
            $query->joinWith(['device', 'bannerGroup', 'place', 'type', 'partners'])->groupBy('id');
        }
        
        return $dataProvider;
    }
}
