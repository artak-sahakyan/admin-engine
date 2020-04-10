<?php

namespace backend\widgets\menu;

use common\models\AdminGroup;

class Menu extends \dmstr\widgets\Menu
{
    protected function renderItems($items)
    {
        // Removing is no access links

        $isAdmin = $this->options['isAdmin'];
        $allowActions = $this->options['allowActions'];

        if ($isAdmin) {
            return parent::renderItems($items);
        }

        foreach ($items as $key => &$item) {
            if (isset($item['items'])) {
                foreach ($item['items'] as $keyy => &$subItem) {
                    $url = is_array($subItem['url']) ? $subItem['url'][0] : $subItem['url'];

                    // remove /admin
                    $pattern = '#^' . preg_quote(\Yii::$app->request->baseUrl, '#') . '/+#';
                    $url = preg_replace($pattern, '', $url);

                    // parse article/unpublished
                    $url = explode('/', trim($url, '/'));
                    $controller = $url[0];
                    $action = $url[1] ?? 'index';

                    // check allow access
                    if (!isset($allowActions[$controller][$action])) {
                        unset($item['items'][$keyy]);
                    }
                }

                // if not items - remove
                if (!sizeof($item['items'])) {
                    unset($items[$key]);
                }
            }
        }
        unset($item, $subItem);

        return parent::renderItems($items);
    }
}