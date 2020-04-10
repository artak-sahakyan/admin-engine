<?php

namespace backend\widgets\ckeditor\actions;

use backend\widgets\ckeditor\models\File;
use Yii;
use yii\base\Security;
use yii\web\ViewAction;
use yii\helpers\Inflector;
use Imagine\Image\Box;
use yii\imagine\Image;
use yii\helpers\FileHelper;

/**
 * Class BrowseAction
 * @package bajadev\ckeditor\actions
 */
class UploadActionCustom extends ViewAction
{
    /**
     * @var Base Url
     */
    public $url;
    /**
     * @var Base Path
     */
    public $path;
    /**
     * @var Base Path Compress
     */
    public $pathCompress;

    /**
     * @var int Max Width
     */
    public $maxWidth = 800;
    /**
     * @var int Max Height
     */
    public $maxHeight = 800;
    /**
     * @var int image quality
     */
    public $quality = 80;
    /**
     * @var bool Use Hash for filename
     */
    public $useHash = true;

    public function init()
    {
        $this->registerTranslations();
    }

    /**
     * Register widget translations.
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['bajadev/ckeditor']) && !isset(Yii::$app->i18n->translations['bajadev/ckeditor/*'])) {
            Yii::$app->i18n->translations['bajadev/ckeditor'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vendor/bajadev/yii2-ckeditor/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'bajadev/ckeditor' => 'ckeditor.php'
                ]
            ];
        }
    }

    public function run()
    {
        $path = $this->getPath($this->path);
        $pathCompress = $this->getPath($this->pathCompress);

        if (!file_exists($path)) {
            FileHelper::createDirectory($path);
            FileHelper::createDirectory($pathCompress);
        }

        if (Yii::$app->request->isPost) {

            $image = \yii\web\UploadedFile::getInstanceByName('upload');
            $model = new File();
            $model->file = $image;

            if ($model->validate()) {
                $fileName = $this->getFileName($image);
                $fileNameExt = $fileName . '.' . $image->getExtension();

                $image->saveAs($path . $fileNameExt);

                if ($this->isAni($path . $fileNameExt)) {
                // for gif without compress

                    copy($path . $fileNameExt, $pathCompress . $fileNameExt);
                } else {
                // other img

                    $imagine = Image::getImagine();
                    $photo = $imagine->open($path . $fileNameExt);

                    $fileNameExt = $fileName . '.jpg';
                    $photo->thumbnail(new Box($this->maxWidth, $this->maxHeight))
                        ->interlace(\Imagine\Image\ImageInterface::INTERLACE_PLANE)
                        ->save($pathCompress . $fileNameExt, ['quality' => $this->quality]);
                }

                if (isset($_GET['CKEditorFuncNum'])) {
                    $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
                    $ckfile = $this->getUrl() . $fileNameExt;
                    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$ckfile', '');</script>";
                    exit;
                }

            } else {
                echo "<script type='text/javascript'>alert('" . $model->getFirstError('file') . "');</script>";
                exit;
            }
        }

    }

    /**
     * @return string
     */
    private function getPath($path)
    {
        return Yii::getAlias($path);
    }

    private function getFileName($image)
    {

        if ($this->useHash) {
            $security = new Security();
            $fileName = $security->generateRandomString(16);

            return $fileName;
        } else {
            return $image->name;
        }
    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return Yii::getAlias($this->url);
    }

    /**
     * Check is image animate.
     * Copy from https://www.php.net/manual/en/function.imagecreatefromgif.php#104473
     * @param $filename
     * @return bool
     */
    private function isAni($filename)
    {
        if(!($fh = @fopen($filename, 'rb')))
            return false;
            $count = 0;
            //an animated gif contains multiple "frames", with each frame having a
            //header made up of:
            // * a static 4-byte sequence (\x00\x21\xF9\x04)
            // * 4 variable bytes
            // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

            // We read through the file til we reach the end of the file, or we've found
            // at least 2 frame headers
            while(!feof($fh) && $count < 2) {
                $chunk = fread($fh, 1024 * 100); //read 100kb at a time
                $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
           }

        fclose($fh);
        return $count > 1;
    }
}
