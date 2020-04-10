<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_partners".
 *
 * @property int $id
 * @property string $name
 *
 * @property BannerVsPartners[] $bannerVsPartners
 */
class BannerPartner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner_partners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['name'], 'string', 'max' => 70],
            [['name', 'alias'], 'unique', 'on'=>'update'],
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
            'alias' => 'Алиас',
        ];
    }

    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['partner_id' => 'id']);
    }
}
