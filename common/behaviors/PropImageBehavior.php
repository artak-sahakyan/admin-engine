<?php

namespace common\behaviors;

use common\components\ColorThief;
use yii\base\Behavior;

class PropImageBehavior extends Behavior
{
    /**
     * Analyze image return dominant rgb color
     * @return bool|array
     */
    public function imageDominantColor()
    {
        $imagePath = $this->owner->getImagePath();

        if (file_exists($imagePath)) {
            $dominantColorRgb = ColorThief::getColor($imagePath);

            return $dominantColorRgb;
        }

        return false;
    }

    /**
     * Analyze image return background rgb color
     * @return bool|array
     */
    public function imageBackgroundColor()
    {
        $imagePath = $this->owner->getImagePath();

        if (file_exists($imagePath)) {
            $dominantColorRgb = ColorThief::getBackgroundColor($imagePath);

            return $dominantColorRgb;
        }

        return false;
    }

    /**
     * Brightness analyze image color
     * @param $colorRgb
     * @return string
     */
    public function getImageBrightness($colorRgb)
    {
        list($red, $green, $blue) = $colorRgb;
        if ( 0.299 * $red + 0.587 * $green + 0.114 * $blue > 127.5 ) {
            $imageBrigtness = 'light';
        }
        else {
            $imageBrigtness = 'dark';
        }

        return $imageBrigtness;
    }

    /**
     * Convert RGB to HSV
     * @param $R
     * @param $G
     * @param $B
     * @return array
     */
    public function convertRGBtoHSV($R, $G, $B)
    {
        $HSL = array();

        $var_R = ($R / 255);
        $var_G = ($G / 255);
        $var_B = ($B / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;

        $V = $var_Max;

        if ($del_Max == 0) {
            $H = 0;
            $S = 0;
        } else {
            $S = $del_Max / $var_Max;

            $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
            $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
            $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

            if ($var_R == $var_Max) $H = $del_B - $del_G;
            else if ($var_G == $var_Max) $H = (1 / 3) + $del_R - $del_B;
            else if ($var_B == $var_Max) $H = (2 / 3) + $del_G - $del_R;

            if ($H < 0) $H++;
            if ($H > 1) $H--;
        }

        $HSL['H'] = $H;
        $HSL['S'] = $S;
        $HSL['V'] = $V;

        return $HSL;
    }
}