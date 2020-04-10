<?php
namespace backend\controllers;

use Yii;
use common\models\BannerPartner;
use backend\controllers\AdminController;

/**
 * BannerPartnerController implements the CRUD actions for BannerPartner model.
 */
class BannerPartnerController extends AdminController
{
    public function init()
    {
        $this->modelClass = BannerPartner::class;
    }
}
