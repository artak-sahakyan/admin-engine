<?php
namespace common\helpers;

use Yii;

class SeoHelper extends FilesHelper
{

    public static function termsSeo()
    {
        return [
            'title' => 'Пользовательское соглашение',
            'description' => '',
            'keywords' => '',
        ];
    }

    public static function siteMapSeo()
    {
        return [
            'title' => 'Карта сайта ' . ucfirst(UrlHelper::getDomain()),
            'description' => 'Карта сайта - на странице размещены ссылки на статьи и разделы сайта.',
            'keywords' => 'карта, сайт, страница, навигация',
        ];
    }

    public static function aboutSeo()
    {
        return [
            'title' => 'О нас',
            'keywords' => '',
            'description' => '',
        ];
    }

    public static function aboutAdvertising()
    {
        return [
            'title' => 'Реклама на сайте ' . ucfirst(UrlHelper::getDomain()),
            'keywords' => '',
            'description' => '',
        ];
    }

    public static function setSeo($object, array  $keys) {

        return [
            'title'         => (isset($object[$keys[0]])) ? $object[$keys[0]] : '',
            'keywords'      => (isset($object[$keys[1]])) ? $object[$keys[1]] : '',
            'description'   => (isset($object[$keys[2]])) ? $object[$keys[2]] : '',
        ];
    }
}
