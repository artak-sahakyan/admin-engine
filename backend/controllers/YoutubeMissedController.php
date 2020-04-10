<?php
namespace backend\controllers;

use backend\models\CronLog;
use common\helpers\FilesHelper;
use Yii;
use common\models\ArticleYoutube;
use backend\controllers\AdminController;

/**
 * YoutubeMissedController implements the CRUD actions for ArticleYoutube model.
 */
class YoutubeMissedController extends AdminController
{
    private $sitePath;
    private $runCommandName = 'youtube-missed';

    public function init()
    {
        $this->sitePath = Yii::getAlias('@sitePath');
        $this->modelClass = ArticleYoutube::class;
        $this->data = [
            'action'    =>  'update-list',
        ];

        // It is running?
        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommandName])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $this->data['running'] = $cronLog['status'] == 'running';

        // Last update
        $cronLog = CronLog::find()
            ->where(['command' => $this->runCommandName, 'status' => 'done'])
            ->orderBy('created_at DESC')
            ->limit(1)
            ->one();
        $this->data['lastUpdate'] = !empty($cronLog['created_at']) ? date('Y-m-d H:i:s', $cronLog['created_at']) : '-';
    }

    /**
     * Update list all youtube links
     * @return page
     */
    public function actionUpdateList()
    {
        $updateMissed = Yii::$app->request->post('updateMissed');

        self::runCommand([0, $updateMissed]);

        \Yii::$app->session->setFlash('warning', "Запущено полная проверка youtube ссылок");

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Update single link
     * @return page
     */
    public function actionUpdateSingle()
    {
        $id = Yii::$app->request->get('id');

        self::runCommand([$id, 0]);

        return $this->redirect(Yii::$app->request->referrer);
    }

    private function runCommand(array $params)
    {
        exec("php " . $this->sitePath . "/yii " . $this->runCommandName . " " . implode(' ', $params) . " > /dev/null 2>/dev/null &");

        return $this;
    }
}
