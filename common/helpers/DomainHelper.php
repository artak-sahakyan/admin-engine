<?php


namespace common\helpers;


class DomainHelper
{
    const SOVETS = 'sovets.net';
    const ALLSLIM = 'allslim.ru';

    public static function currentIs($domainName) {
        return \Yii::$app->params['currentSiteHost'] == $domainName;
    }
}