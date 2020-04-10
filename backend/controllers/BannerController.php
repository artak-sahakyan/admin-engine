<?php

namespace backend\controllers;

use Yii;
use common\models\{ Banner, BannerPartner, BannerGroup, BannerType };
use backend\controllers\AdminController;
use backend\events\ControllerModelSaveEvent;
use yii\helpers\ArrayHelper;

/**
 * BannerController implements the CRUD actions for Banner model.
 */
class BannerController extends AdminController
{
    public function init()
    {
        $this->modelClass = Banner::class;
        $this->on(static::MODEL_AFTER_SAVE_EVENT, [$this, 'bannerSave']);
    }

    public function bannerSave(ControllerModelSaveEvent $event)
    {
        $inputData = Yii::$app->request->post('Banner');

        if(!empty($inputData['partners'])) {
            $partner_ids = $inputData['partners'];
            $partner_ids = (is_array($partner_ids)) ? $partner_ids : [$partner_ids];
            
            $event->model->updateLinks($partner_ids, 'partners', BannerPartner::class);
        }
    }

    public function actionGetContentContainer()
    {
        $type_id        = Yii::$app->request->get('type_id');
        $currentContent = Yii::$app->request->get('currentContent');

        if($type_id) {
            $bannerType = BannerType::findOne($type_id);

            $model = new Banner;
            $model->content = $currentContent;

            $data = array('model' => $model);

            return $this->renderAjax('containers/_' . $bannerType->slug, $data, false, true);
        }
    }

    public function actionAdditional()
    {
        $selectedBanners    = explode(',', Yii::$app->request->get('selectedBanners'));
        $action             = 'mass'.Yii::$app->request->get('massActionForm');
        $additional         = Yii::$app->request->get('additionalDropDown');

        try {
            foreach($selectedBanners as $bannerId){
                $banner = Banner::findOne($bannerId);
                if(!empty($banner)) {
                    $this->$action($banner, $additional);
                }
            }
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('danger', "Ошибка выполнения массового действия");
        }
        
        return $this->actionIndex();
    }

    public function actionAjaxGetList($ajaxGetList)
    {
        if(Yii::$app->request->isAjax && $ajaxGetList) {
            return json_encode(ArrayHelper::map($this->$ajaxGetList(), 'id', 'name'), JSON_UNESCAPED_UNICODE);
        }

        return false;
    }

    private function massCopy(Banner $banner)
    {
        $model = new Banner();
        $model->attributes = $banner->attributes;
        $model->attributes = [
            'isNewRecord'   => false,
            'id'            => null,
            'name'          => $model->name . '-copy',
        ];

        $result = $model->save(false);

        if($result && !empty($banner->partners)) {
            $partner_ids = [];

            foreach($banner->partners as $partner) {
                $partner_ids[] = $partner->id;
            }
            
            $model->updateLinks($partner_ids, 'partners', BannerPartner::class);
        }

        return $result;
    }

    private function massTurnOff(Banner $banner)
    {
        $banner->setActive(false);

        return $banner->save(false);
    }

    private function massTurnOn(Banner $banner)
    {
        $banner->setActive(true);

        return $banner->save(false);
    }

    private function massSetParnter(Banner $banner, int $additional)
    {
        $banner->setPartner($additional);

        return $banner->save(false);
    }

    private function massSetGroup(Banner $banner, int $additional)
    {
        $banner->setGroup($additional);
        
        return $banner->save(false);
    }

    private function massDeleteAll(Banner $banner)
    {
        return $banner->delete();
    }

    private function massGetGroups()
    {
        return BannerGroup::find()->all();
    }

    private function massGetPartners()
    {
        return BannerPartner::find()->all();
    }
}
