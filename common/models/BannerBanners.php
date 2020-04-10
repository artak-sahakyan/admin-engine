<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_banners".
 *
 * @property int $id
 * @property int $type_id
 * @property int $is_active
 * @property int $is_scroll_fix
 * @property int $place_id
 * @property int $device_id
 * @property int $group_id
 * @property string $content
 * @property int $created_at
 * @property string $name
 */
class BannerBanners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%banner_banners}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'is_active', 'is_scroll_fix', 'place_id', 'device_id', 'group_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'is_active' => 'Is Active',
            'is_scroll_fix' => 'Is Scroll Fix',
            'place_id' => 'Place ID',
            'device_id' => 'Device ID',
            'group_id' => 'Group ID',
            'content' => 'Content',
            'created_at' => 'Created At',
            'name' => 'Name',
        ];
    }
}
