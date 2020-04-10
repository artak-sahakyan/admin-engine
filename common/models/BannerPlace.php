<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banner_places".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 *
 * @property BannerVsPlaces[] $bannerVsPlaces
 */
class BannerPlace extends \yii\db\ActiveRecord
{
    const NEEDLE = "[banner]";

    private $bannerCode;

    public static function tableName()
    {
        return '{{%banner_places}}';
    }

    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['name', 'alias'], 'string', 'max' => 50],
            [['name'], 'unique', 'on'=>'update'],
            [['container'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'alias' => 'Идентификатор',
            'container' => 'Контейнер',
        ];
    }

    public function getBanners()
    {
        return $this->hasMany(Banner::class, ['place_id' => 'id']);
    }

    public function getBannersCount() {
        return $this->getBanners()->count(); 
    }

    public function getBannerCode()
    {
        return $this->bannerCode;
    }

    public function insertBanner(Banner $banner)
    {
        if(strripos($this->container, self::NEEDLE)) {
            $this->bannerCode = str_replace(self::NEEDLE, $banner->content, $this->container);
        } else {
            $this->bannerCode = $banner->content;
        }
        return $this->bannerCode;
    }

}
