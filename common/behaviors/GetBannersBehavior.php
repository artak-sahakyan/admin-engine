<?php
namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use common\models\{ 
    Banner,
    BannerGroup
};

class GetBannersBehavior extends Behavior
{
    public static $alias;
    private static $bannerGroup;

    /**
     * @param string $alias
     * @param null|BannerGroup $bannerGroup
     * @return Banner
     */
    public function getOneBanner(string $alias, BannerGroup $bannerGroup = null)
    {
        self::$alias = $alias;
        self::$bannerGroup = $bannerGroup;

        return self::getQuery()->one();
    }

    /**
     * @param null|BannerGroup $bannerGroup
     * @param null|array $places
     * @return array
     */
    public static function getAllBanners(BannerGroup $bannerGroup = null, array $places = null)
    {
        self::$bannerGroup = $bannerGroup;

        return self::getQuery()->andWhere(['in', 'place.alias', $places])->all();
    }

    /**
     * @return ActiveRecord
     */
    private function getQuery()
    {
        // hardcode - failed to inject this param out this class, because this class use as static and non static
        // this called from GenerateRssController and Yii::$app->request->get missing!
        $bannerServiceDefault = Banner::SERVICE_REALBIG;
        try {
            $service = Yii::$app->request->get('ad', $bannerServiceDefault);
        } catch (\Throwable $e) {
            $service = $bannerServiceDefault;
        }

        $device_id = Yii::getAlias('@device_id'); 

        $banner = Banner::find()
            ->joinwith(['place as place'])
            ->andWhere([
                'or',
                ['device_id' => $device_id],
                ['device_id' => 1] 
            ])
            ->andWhere([
                'is_active' => true,
                'service' => $service,
            ]);

        if(self::$alias) {
            $banner = $banner->andWhere([
                'place.alias' => self::$alias
            ]);
        }

        $banner = $banner->orderBy('device_id DESC');

        if(self::$bannerGroup) {
            $banner = $banner->joinwith('bannerGroup as bannerGroup');
                
            if(!self::$bannerGroup->show_default_group) {
                $banner = $banner->andWhere(
                    ['group_id' => self::$bannerGroup->id]
                );
            } else {
                $banner = $banner->andWhere([
                    'or',
                    ['group_id' => self::$bannerGroup->id],
                    [
                        'or', 
                        ['group_id' => 0], 
                        ['group_id' => null]
                    ]
                ]);
            }
            $banner = $banner->orderBy('group_id DESC');
        } else {
            $banner = $banner->andWhere([
                'or',
                ['group_id' => 0],
                ['group_id' => null]
            ]);
        }
        return $banner;
    }
}
