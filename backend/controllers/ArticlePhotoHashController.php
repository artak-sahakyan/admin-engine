<?php
namespace backend\controllers;

use common\helpers\FilesHelper;
use Yii;
use common\models\ArticlePhotoHash;

/**
 * ArticlePhotoHashController implements the CRUD actions for ArticlePhotoHash model.
 */
class ArticlePhotoHashController extends AdminController
{
    private $sitePath;

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
        $this->modelClass = ArticlePhotoHash::class;
    }

    /**
     * List all double photos from content.
     * @return page
     */
    public function actionDoublePhotosContent()
    {
    	$params = ['type' => ArticlePhotoHash::CONTENT];
        $this->params           = $params;
        $this->data['action']   = 'update-content';
        $this->data['title']    = 'Дублирование фотографий в статьях';
        $this->data['running']  = FilesHelper::checkIsRunningProcess('CalcPhotosHashController');
        return $this->actionIndex();
    }

    /**
     * Lists all double preview photos
     * @return page
     */
    public function actionDoublePhotosPreview()
    {
    	$params = ['type' => ArticlePhotoHash::PREVIEW];
        $this->params           = $params;
        $this->data['action']   = 'update-preview';
        $this->data['title']    = 'Дублирование фотографий превью';
        $this->data['running']  = FilesHelper::checkIsRunningProcess('CalcPhotosHashController');
        return $this->actionIndex();
    }

    /**
     * Start update list photos hashes from content
     * @return page
     */
    public function actionUpdateContent()
    {
        self::runCommand([ArticlePhotoHash::CONTENT]);
        \Yii::$app->session->setFlash('warning', "Запущено полное обновление изображений из статей");

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Start update list preview photos
     * @return page
     */
    public function actionUpdatePreview()
    {
        self::runCommand([ArticlePhotoHash::PREVIEW]);
        \Yii::$app->session->setFlash('warning', "Запущено полное обновление превью изображений");

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Run php yii command with params
     * @param array $params
     * @return $this
     */
    private function runCommand(array $params)
    {
        exec("php " . $this->sitePath . "/yii calc-photos-hash " . implode(' ', $params) . " > /dev/null 2>/dev/null &");

        return $this;
    }
}
