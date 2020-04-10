<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FrontendAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/frontendAssets';
    public $js = [
        [
            'js/article.js',
            'async' => 'async',
        ]
    ];
}
