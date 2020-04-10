<?php

namespace common\components\minify;

use maybeworks\minify\MinifyManager;
use yii\base\Event;

/**
 * Class CustomMinifyManager
 * We disabled remove html comments
 *
 * @package common\components\minify
 */
class CustomMinifyManager extends MinifyManager
{
    public function onEventBeforeSend(Event $event)
    {
        $response = $event->sender;

        if ($this->html & in_array($response->format, $this->formats)) {
            if (!empty($response->data)) {
                $response->data = CustomMinifyHelper::html($response->data);
            }
            if (!empty($response->content)) {
                $response->content = CustomMinifyHelper::html($response->content);
            }
        }
    }
}