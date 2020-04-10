<?php
namespace common\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper {

    public static function mergeByCondition($condition , Array $firstPart, Array $secondPart) {
        return ($condition) ? array_merge($firstPart, $secondPart) : $firstPart;
    }

}