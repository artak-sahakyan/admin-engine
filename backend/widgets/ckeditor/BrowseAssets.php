<?php


namespace backend\widgets\ckeditor;

use yii\web\AssetBundle;

class BrowseAssets extends AssetBundle
{
    public $sourcePath = '@ckeditor/editor';

    public $js = [
        'assets/jquery.lazyload.min.js',
        'assets/js.cookie-2.0.3.min.js',
        'assets/function.js'
    ];

    public $css = [
        'assets/styles.css',
        'assets/ckeditor.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}