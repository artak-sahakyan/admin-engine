<?php

namespace common\widgets;


use common\models\Meta;
use yii\base\Widget;

class MetaWidget extends Widget
{
    /* @todo upgrade in future  */
    public function run() {
        $meta = Meta::find()->asArray()->one();
        return ($meta) ? $this->render('meta', compact('meta')) : '';
    }
}