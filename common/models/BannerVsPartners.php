<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_vs_partners".
 *
 * @property int $id
 * @property int $banner_id
 * @property int $partner_id
 *
 * @property BannerBanners $banner
 * @property BannerPartners $partner
 */
class BannerVsPartners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner_vs_partners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['banner_id', 'partner_id'], 'required'],
            [['banner_id', 'partner_id'], 'integer'],
            [['banner_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerBanners::class, 'targetAttribute' => ['banner_id' => 'id']],
            [['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => BannerPartners::class, 'targetAttribute' => ['partner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'banner_id' => 'Banner ID',
            'partner_id' => 'Partner ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanner()
    {
        return $this->hasOne(BannerBanners::class, ['id' => 'banner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(BannerPartners::class, ['id' => 'partner_id']);
    }
}
