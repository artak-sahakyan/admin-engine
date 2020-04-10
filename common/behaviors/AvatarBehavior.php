<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use Imagine\Image\Box;
use yii\helpers\{ FileHelper, Url };
use \yii\imagine\Image;

/* @property $publicId*/

class AvatarBehavior extends Behavior
{

    public $previewPath = '/avatar';
    public $quality = 70;

    public function avatar($width = null, $height = null, $mode = 'big')
    {
        if(is_file($this->getImagePath($width, $height))) return $this->getImageUrl(0, $width, $height);

        if (!is_file($this->getImagePath())) {
            return $this->getImageUrl(1);
        }

        try {
            $originalFile = $this->getImagePath();
            if(file_exists($originalFile)) {
                $this->resize($width, $height);
            }

            return Url::to( $this->previewPath . '/' . $this->fileName($width, $height));
        } catch (\Exception $e ) {
            return $this->getImageUrl(1);
        }
    }



    public function defaultPath() {
        return  '/big_icon.jpg';
    }

    public function fileName($width = null, $height = null) {
        return 'users/' . $this->owner->expert->id . '/avatar' .  (($width && $height) ? '_' . $width . 'x' . $height : '') . '.jpg';
    }

    /**
     * @param null|int $width
     * @param null|int $height
     * @return string
     */
    public function getImagePath($width = null, $height = null)
    {
        return $this->getImageDirectory() . '/' . $this->fileName($width, $height);
    }



    /**
     * @return string
     */
    public function getImageDirectory()
    {
        $imageDir = \Yii::getAlias('@siteFrontend') . '/web' . $this->previewPath;

        if (!file_exists($imageDir)) {
            mkdir($imageDir);
        }
        return $imageDir;
    }


    /**
     * @param int $default
     * @param null $width
     * @param null $height
     * @return string
     */
    public function getImageUrl($default = 1, $width = null, $height = null)
    {
        return Url::to( $this->previewPath . '/' . (($default) ? $this->defaultPath() :  $this->fileName($width, $height)));
    }


    /**
     * @param null $width
     * @param null $height
     * @return string
     */
    protected function resize($width = null, $height = null)
    {
        $filePath = $this->getImagePath($width, $height);

        $imagine = Image::getImagine();
        $image = $imagine->open($this->getImagePath());
        $image->resize(new Box($width, $height))->save($filePath, ['quality' => $this->quality]);

        return true;
    }

    /**
     * @param null $width
     * @param null $height
     * @return bool
     */
    protected function crop($width = null, $height = null)
    {
        $filePath =  $this->getImagePath($width, $height);

        Image::crop($this->getImagePath(), $width, $height)->save($filePath, ['quality' => $this->quality]);

        return true;
    }
}