<?php
namespace backend\widgets\ckeditor;

use Yii;
use yii\web\AssetBundle;

class Assets extends AssetBundle{
	//public $sourcePath = '@vendor/bajadev/yii2-ckeditor/editor';
	public $sourcePath = '@backend/widgets/ckeditor/editor';

    public $js = [
        'ckeditor.js',
		'assets/js.js',
    ];
	public $depends = [
		'yii\web\YiiAsset'
	];
}