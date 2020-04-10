<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_types".
 *
 * @property int $id
 * @property string $name
 *
 * @property BannerBanners[] $bannerBanners
 */
class BannerType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 70],
            [['name', 'slug'], 'unique', 'on'=>'update'],
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
            'slug' => 'Алиас',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['type_id' => 'id']);
    }
}
