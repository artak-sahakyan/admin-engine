<?php

namespace common\behaviors;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use \yii\imagine\Image;
use Yii;
use yii\base\Behavior;

/* @property $publicId*/

class ImageBehavior extends Behavior
{

    public $previewPath = '/photos';
    public $quality = 70;

    /**
     * @return string
     */
    public function getImageDirectory()
    {
        $imageDir = \Yii::getAlias('@siteFrontend')  . '/web'  . $this->previewPath . $this->publicId;

        if(!file_exists($imageDir)) {
            mkdir($imageDir);
            chmod($imageDir, 0777);
        }
        return $imageDir;
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->getImageDirectory() . '/origin.' . (($this->owner->image_extension) ? $this->owner->image_extension : 'jpg');
    }

    /**
     * @param bool $origin
     * @return string
     */
    public function getImageUrl($origin = null)
    {
        return Url::to((!$origin)  ? $this->previewPath . $this->publicId . '/origin.' . $this->owner->image_extension : $origin);
    }

    /**
     * @param null $width
     * @param null $height
     * @param string $mode
     * @return mixed|string
     * @throws \Exception
     */
    public function getThumb($width = null, $height = null, $mode = 'origin')
    {
        if (!is_file($this->getImagePath())) {
            return $this->placeholdUrl($width, $height);
        }

        $originalFile = $this->filePath($width, $height);

        if ($mode == 'origin') {

            if(!file_exists($originalFile)) {
                $this->resize($width, $height);
            }

            return $this->webPath($width, $height);
        }

        if(!method_exists($this, $mode)) {
            throw new \Exception("Mode [$mode] is not available");
        }

        return $this->$mode($width, $height);
    }

    protected function aspectRatio(int $width, int $height)
    {
        $filePath = $this->getImageDirectory() . '/' . $this->imageName($width, $height, 'aspectRatio');

        if (file_exists($filePath) == false) {
            $imagine = Image::getImagine();
            $imagine->open($this->getImagePath())
                ->thumbnail(new Box($width, $height))
                ->interlace(ImageInterface::INTERLACE_PLANE)
                ->save($filePath, ['quality' => $this->quality]);
        }
        return $this->getImageUrl($this->previewPath . '/' . $this->publicId . '/' . $this->imageName($width, $height, 'aspectRatio'));
    }


    /**
     * @param null $width
     * @param null $height
     * @return string
     */
    protected function resize($width = null, $height = null)
    {

        $filePath = $this->filePath($width, $height);

        $imagine = Image::getImagine();
        $image = $imagine->open($this->getImagePath());
        $image->resize(new Box($width, $height))
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($filePath, ['quality' => $this->quality]);

        return $this->getImageUrl($this->previewPath . '/' . $this->publicId . '/' . $this->imageName($width, $height));
    }


    protected function crop($width = null, $height = null)
    {
        $filePath = $this->filePath($width, $height);

        // return old algorithm
        $manager = new \Intervention\Image\ImageManager;
        $manager->make($this->getImagePath())->fit($width, $height)->interlace()->save($filePath);

        return $this->getImageUrl($this->previewPath . '/' .$this->publicId . '/' . $this->imageName($width, $height));
    }



    /**
     * @return string
     */
    public function getImagePreviewUrl()
    {
        return $this->getThumb(50, 50, 'crop');
    }

    /**
     * @return string
     */
    public function getImageExtension()
    {
        return $this->owner->image_extension;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return md5($this->owner->id);
    }


    /**
     * @param null $width
     * @param null $height
     * @return string
     */
    public function filePath($width = null, $height = null)
    {
        if($width && $height) {
            return $this->getImageDirectory() . '/' . $this->imageName($width, $height);
        }

        return $this->getImagePath() ;
    }


    /**
     * @param null $width
     * @param null $height
     * @return string
     * @throws \Exception
     */
    public function webPath($width = null, $height = null)
    {
        if($width && $height) {

            return $this->resizedImagePath($width, $height);
        }

        return $this->getImageUrl() ;
    }

    /**
     * @param $width
     * @param $height
     * @return string
     */
    protected function resizedImagePath($width, $height)
    {
        return $this->getImageUrl($this->previewPath . $this->publicId . '/' . $this->imageName($width, $height));
    }

    /**
     * @param null|number $width
     * @param null|number $height
     * @return string
     */
    protected function imageName($width = null, $height = null, $mode = '')
    {
        if($width && $height) {
            // [a]spect ratio
            if ($mode == 'aspectRatio') {
                $mode = 'a';
            } else {
                $mode = '';
            }

            $fileName = $this->owner->slug;
            if (!empty($mode)) {
                $fileName .= '_m' . $mode;
            }
            $fileName .= '_w' . $width . '_h' . $height;
            $fileName .= '.' . (($this->owner->image_extension) ? $this->owner->image_extension : 'jpg');
            return $fileName;
        }

        return 'origin.' . (($this->owner->image_extension) ? $this->owner->image_extension : 'jpg');
    }

    /**
     * @param null $width
     * @param null $height
     * @return string
     */
    public function originPreview($width = null, $height = null)
    {
        $originalFile = $this->getImageDirectory() . '/' . $this->imageName();

        if (!is_file($originalFile)) {
            return $this->placeholdUrl($width, $height);
        }

        return $originalFile;

    }

    /**
     * @param $width
     * @param $height
     * @return string
     */
    public function placeholdUrl($width, $height)
    {
        return "https://dummyimage.com/{$width}x{$height}/EFEFEF/AAAAAA";
    }

    public function uploadImage()
    {
        $model = $this->owner;
        if(!$model->isNewRecord) {
            $this->deleteImages();
        }

        $imageSizes = getimagesize($model->imageFile->tempName);
        $model->image_extension = $model->imageFile->extension;
        $savePath = $this->getImagePath();
        
        $model->imageFile->saveAs($savePath);
        chmod($savePath, 0777);
        $imagine = Image::getImagine();
        Image::thumbnail($imagine->open($savePath), $imageSizes[0], $imageSizes[1])
            ->interlace(ImageInterface::INTERLACE_PLANE)
            ->save($savePath, ['quality' => $this->quality]);

        $model->imageFile = null;
        $model->save(false);

    }

    public function deleteImages($returnDefault = false)
    {
        $result = false;

        if($files = FileHelper::findFiles($this->getImageDirectory())) {
            foreach ($files as $filePath) {
                FileHelper::unlink($filePath);
            }

            $result = true;
        }

        if($returnDefault) {
            $result = $this->placeholdUrl(300, 200);
        }

        return $result;
    }

}