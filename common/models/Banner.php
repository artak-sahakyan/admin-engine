<?php

namespace common\models;
use common\behaviors\UpdateLinksBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "{{%banner_banners}}".
 *
 * @property int $id
 * @property int $type_id
 * @property int $is_active
 * @property int $is_scroll_fix
 * @property string $name
 * @property string $place_id
 * @property string $device_id
 * @property string $group_id
 * @property string $content
 * @property string $note
 * @property string $service
 * @property string $created_at
 */

class Banner extends \yii\db\ActiveRecord
{
    const SERVICE_DFP = 'dfp';
    const SERVICE_REALBIG = 'realbig';
    const SERVICE_AMP = 'amp';

    public static function tableName()
    {
        return 'banner_banners';
    }

    public function rules()
    {
        return [
            [['type_id', 'place_id', 'is_active', 'is_scroll_fix', 'created_at', 'device_id', 'group_id'], 'integer'],
            [['name', 'type_id', 'service'], 'required'],
            [['content', 'note'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique', 'on'=>'update'],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerType::class, 'targetAttribute' => ['type_id' => 'id']],
            [['place_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerPlace::class, 'targetAttribute' => ['place_id' => 'id']],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerDevice::class, 'targetAttribute' => ['device_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerGroup::class, 'targetAttribute' => ['group_id' => 'id']],

            [['service'], 'default', 'value' => static::SERVICE_REALBIG],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Вид баннера',
            'place_id' => 'Рекламное место',
            'is_active' => 'Активность',
            'is_scroll_fix' => 'Фиксировать при скроле',
            'name' => 'Название',
            'content' => 'Содержимое',
            'note' => 'Заметка',
            'device_id' => 'Устройства',
            'group_id' => 'Группа статей',
            'partners' => 'Партнерка',
            'service' => 'Сервис предоставляющий баннер',
            'created_at' => 'Создан'
        ];
    }

    public function behaviors()
    {
        return [
            UpdateLinksBehavior::class,
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    public function setActive($active)
    {
        return $this->is_active = $active;
    }

    public function setPartner(int $partner_id)
    {
        $partner = BannerPartner::find()->where('id = ' . $partner_id)->one();
        if($partner) {
            $this->link('partners', $partner);
        }
    }

    public function setGroup(int $group_id)
    {
        return $this->group_id = $group_id;
    }

    public function getType()
    {
        return $this->hasOne(BannerType::class, ['id' => 'type_id']);
    }

    public function getPlace()
    {
        return $this->hasOne(BannerPlace::class, ['id' => 'place_id']);
    }

    public function getDevice()
    {
        return $this->hasOne(BannerDevice::class, ['id' => 'device_id']);
    }

    public function getBannerGroup()
    {
        return $this->hasOne(BannerGroup::class, ['id' => 'group_id']);
    }

    public function getBannerVsPartners()
    {
        return $this->hasMany(BannerVsPartners::class, ['banner_id' => 'id']);
    }

    public function getPartners() {
        return $this->hasMany(BannerPartner::class, ['id' => 'partner_id'])
          ->via('bannerVsPartners')->indexBy('id');
    }

    public function getCode()
    {
        return $this->place->insertBanner($this);
    }

    public function serviceLabel()
    {
        return [
            static::SERVICE_DFP => ucfirst(static::SERVICE_DFP),
            static::SERVICE_REALBIG => ucfirst(static::SERVICE_REALBIG),
            static::SERVICE_AMP => ucfirst(static::SERVICE_AMP),
        ];
    }
}
