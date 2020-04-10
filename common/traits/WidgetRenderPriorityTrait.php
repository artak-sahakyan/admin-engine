<?php

namespace common\traits;

trait WidgetRenderPriorityTrait
{
    /**
     * First looking for template on front second backend
     *
     * @param $view
     * @param array $params
     * @return mixed
     */
    public function render($view, $params = [])
    {
        $frontendView = '/widgets/views/' . $view;
        if (!preg_match('#\.#', $view)) {
            $frontendView .= '.php';
        }
        if (file_exists(\Yii::getAlias('@siteCommon') . $frontendView)) {
            $view = '@siteCommon' . '/widgets/views/' . $view;
        }

        return parent::render($view, $params);
    }
}