<?php

namespace backend\controllers;

use Yii;

class SitemapGenerateController extends \yii\web\Controller
{
    private $sitePath;

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
    }

    public function actionIndex()
    {
        $this->runCommand();
        \Yii::$app->session->setFlash('warning', "Запущена генерация карты сайта");

        return $this->redirect(Yii::$app->request->referrer);
    }

    private function runCommand()
    {
        exec("php " . $this->sitePath . "/yii generate-sitemap  > /dev/null 2>/dev/null &");

        return $this;
    }
}
