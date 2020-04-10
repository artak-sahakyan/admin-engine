<?php
namespace common\widgets;

use common\models\Counter;
use yii\base\Widget;

class CounterWidget extends Widget
{
    public function run()
    {
        $counterCodes = Counter::find()
	        ->select(['code'])
	        ->where(['turn_on' => 1])
	        ->orderBy('sort')
	        ->column();

        return implode('', $counterCodes);
    }
}