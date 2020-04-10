<?php

namespace common\components;

use \ColorThief\ColorThief as LibColorThief;
use \ColorThief\Image\ImageLoader;
use \SplFixedArray;

class ColorThief extends LibColorThief
{
    /**
     * Threshold white
     * @var bool
     */
    protected static $thresholdWhite = true;

    /**
     * Can work with several areas
     *
     * @param mixed      $sourceImage Path/URL to the image, GD resource, Imagick instance, or image as binary string
     * @param int        $quality     Analyze every $quality pixels
     * @param array      $histo       Histogram
     * @param array|null $areas
     *
     * @return int
     */
    protected static function loadImage($sourceImage, $quality, array &$histo, array $areas = null)
    {
        $loader = new ImageLoader();
        $image = $loader->load($sourceImage);
        $width = $image->getWidth();
        $height = $image->getHeight();

        // Fill a SplArray with zeroes to initialize the 5-bit buckets and avoid having to check isset in the pixel loop.
        // There are 32768 buckets because each color is 5 bits (15 bits total for RGB values).
        $totalBuckets = (1 << (3 * self::SIGBITS));
        $histoSpl = new SplFixedArray($totalBuckets);
        for ($i = 0; $i < $totalBuckets; $i++) {
            $histoSpl[$i] = 0;
        }

        $numUsefulPixels = 0;

        if ($areas == null) {
            $areas = [
                [],
            ];
        }
        foreach ($areas as $area) {
            $startX = isset($area['x']) ? $area['x'] : 0;
            $startY = isset($area['y']) ? $area['y'] : 0;
            $width = isset($area['w']) ? $area['w'] : ($width - $startX);
            $height = isset($area['h']) ? $area['h'] : ($height - $startY);

            if ((($startX + $width) > $image->getWidth()) || (($startY + $height) > $image->getHeight())) {
                throw new \InvalidArgumentException('Area is out of image bounds.');
            }

            $pixelCount = $width * $height;

            for ($i = 0; $i < $pixelCount; $i += $quality) {
                $x = $startX + ($i % $width);
                $y = (int)($startY + $i / $width);
                $color = $image->getPixelColor($x, $y);

                // Pixel is too transparent. Its alpha value is larger (more transparent) than THRESHOLD_ALPHA.
                // PHP's transparency range (0-127 opaque-transparent) is reverse that of Javascript (0-255 tranparent-opaque).
                if ($color->alpha > self::THRESHOLD_ALPHA) {
                    continue;
                }

                // Pixel is too white to be useful. Its RGB values all exceed THRESHOLD_WHITE
                if (static::$thresholdWhite && $color->red > parent::THRESHOLD_WHITE && $color->green > parent::THRESHOLD_WHITE && $color->blue > parent::THRESHOLD_WHITE) {
                    continue;
                }

                // Count this pixel in its histogram bucket.
                $numUsefulPixels++;
                $bucketIndex = static::getColorIndex($color->red, $color->green, $color->blue);
                $histoSpl[$bucketIndex] = $histoSpl[$bucketIndex] + 1;
            }
        }

        // Copy the histogram buckets that had pixels back to a normal array.
        $histo = [];
        foreach ($histoSpl as $bucketInt => $numPixels) {
            if ($numPixels > 0) {
                $histo[$bucketInt] = $numPixels;
            }
        }

        // Don't destroy a resource passed by the user !
        // TODO Add a method in ImageLoader to know if the image should be destroy
        // (or to know the detected image source type)
        if (is_string($sourceImage)) {
            $image->destroy();
        }

        return $numUsefulPixels;
    }


    /**
     * @param $sourceImage
     * @param int $quality
     * @return array|bool
     */
    public static function getBackgroundColor($sourceImage, $quality = 10)
    {
        $loader = new ImageLoader();
        $image = $loader->load($sourceImage);
        $width = $image->getWidth();
        $height = $image->getHeight();
        $widthLimit = floor($width / 100 * 10);
        $heightLimit = floor($height / 100 * 10);

        // take edges
        $areas = [
            // hoizontal
            [
                'x' => 0,
                'y' => 0,
                'w' => $width,
                'h' => $heightLimit,
            ],
            [
                'x' => 0,
                'y' => $height - $heightLimit,
                'w' => $width,
                'h' => $heightLimit,
            ],

            // vertical
            [
                'x' => 0,
                'y' => $heightLimit,
                'w' => $widthLimit,
                'h' => $height - $heightLimit * 2,
            ],
            [
                'x' => $width - $widthLimit,
                'y' => $heightLimit,
                'w' => $widthLimit,
                'h' => $height - $heightLimit * 2,
            ]
        ];

        static::$thresholdWhite = false;
        return static::getColor($sourceImage, $quality, $areas);
    }
}