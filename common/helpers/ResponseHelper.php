<?php
namespace common\helpers;

use Yii;
use common\models\ManualResponse;

class ResponseHelper
{
    public static function checkAndRedirect(string $url)
    {
        $response = ManualResponse::find()->where(['url' => $url])->one();
        if($response) {
            throw new \yii\web\HttpException($response->code);
         }
    }
}
