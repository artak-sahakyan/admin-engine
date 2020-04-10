<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_devices".
 *
 * @property int $id
 * @property string $name
 *
 * @property BannerVsDevices[] $bannerVsDevices
 */
class BannerDevice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner_devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 70],
            [['name'], 'unique', 'on'=>'update'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBannerVsDevices()
    {
        return $this->hasMany(BannerVsDevices::class, ['device_id' => 'id']);
    }
}
