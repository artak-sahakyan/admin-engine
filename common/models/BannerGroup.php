<?php

namespace common\models;
use common\behaviors\UpdateLinksBehavior;

use Yii;

/**
 * This is the model class for table "banner_groups".
 *
 * @property int $id
 * @property string $name
 * @property int $show_default_group
 */
class BannerGroup extends \yii\db\ActiveRecord
{
    public $articlesCount;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banner_groups}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['show_default_group'], 'integer'],
            [['name'], 'string', 'max' => 70]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'show_default_group' => 'Показывать баннеры из дефолтной группы',
            'articles' => 'Статьи',
            'banners' => 'Баннеры',
        ];
    }

    public function behaviors()
    {
        return [
            UpdateLinksBehavior::class,
        ];
    }

    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['group_id' => 'id']);
    }

    public function getArticles() {
        return $this->hasMany(Article::class, ['banner_group_id' => 'id']);
    }
}
