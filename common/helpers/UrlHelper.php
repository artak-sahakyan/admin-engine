<?php

namespace common\helpers;


use yii\helpers\Url;

class UrlHelper
{
    /**
     * Return domain
     *
     * @param null $domain
     * @return string
     */
    public static function getDomain($domain = null)
    {
        if (!isset($domain)) {
            $domain = Url::base(true);
        }

        return preg_replace('#https?://([^/]+).*#iu', '$1', $domain);
    }
}