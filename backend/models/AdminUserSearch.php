<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;
use backend\interfaces\SearchInterface;

/**
 * AdminUserSearch represents the model behind the search form of `common\models\Admin`.
 */
class AdminUserSearch extends Admin implements SearchInterface
{
    public $adminGroups;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'register_date', 'last_login_date', 'is_active', 'restrict_by_ip'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'register_ip', 'settings', 'ips', 'adminGroups'], 'safe'],
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
        $query = Admin::find();

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
            self::tableName() . '.id' => $this->id,
            self::tableName() . '.is_active' => $this->is_active,
        ]);

        if(array_key_exists('adminGroups', Yii::$app->request->get('AdminUserSearch', []))) {
            $query->joinWith(['adminsVsGroups'])
                ->andFilterWhere(['group_id' => Yii::$app->request->get('AdminUserSearch')['adminGroups']]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        if(!$this->id) {
            $query->joinWith(['adminGroups'])->groupBy('id');
        }

        return $dataProvider;
    }
}
